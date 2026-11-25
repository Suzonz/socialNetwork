// ============================================================
//  DÉMO EN LIGNE : les contrôleurs PHP (src/controllers), mais dans le navigateur
//  Adresses : #/ (accueil), #/login, #/register, #/post, #/edit/12, #/logout
//  Comptes, posts et connexion sont gardés dans sessionStorage (le stockage de
//  l'onglet) : chaque visiteur a son propre Catbook, effacé à la fermeture de
//  l'onglet. Rien n'est envoyé à un serveur. Le mot de passe n'est gardé que
//  sous forme d'empreinte (SHA-256), jamais en clair.
// ============================================================
const KEY = "catbook-demo";

// Quelques posts d'exemple pour que le fil ne soit pas vide
const now = Date.now();
const EXAMPLE = {
    users: [],
    posts: [
        { id: 1, author: "Minou", title: "Premier post !", content: "Bonjour Catbook.\nJ'ai fait trois siestes aujourd'hui.", created_at: new Date(now - 26 * 3600e3).toISOString() },
        { id: 2, author: "Caramel", title: "Alerte croquettes", content: "La gamelle est vide depuis au moins cinq minutes.", created_at: new Date(now - 2 * 3600e3).toISOString() }
    ],
    pseudo: null
};

// ---------- Stockage ----------
function load() {
    try {
        return JSON.parse(sessionStorage.getItem(KEY)) || structuredClone(EXAMPLE);
    } catch {
        return structuredClone(EXAMPLE);
    }
}

let state = load();

function save() {
    try {
        sessionStorage.setItem(KEY, JSON.stringify(state));
    } catch {
        // stockage indisponible : la démo marche quand même jusqu'au rechargement
    }
}

async function fingerprint(password) {
    const data = new TextEncoder().encode(password);
    const hash = await crypto.subtle.digest("SHA-256", data);
    return [...new Uint8Array(hash)].map(b => b.toString(16).padStart(2, "0")).join("");
}

// ---------- Construction des pages (textContent : le texte tapé s'affiche comme du texte) ----------
function h(tag, attrs = {}, ...children) {
    const el = document.createElement(tag);
    for (const [k, v] of Object.entries(attrs)) {
        if (k === "text") el.textContent = v;
        else if (k.startsWith("on")) el.addEventListener(k.slice(2), v);
        else el.setAttribute(k, v);
    }
    el.append(...children.filter(Boolean));
    return el;
}

const go = (path) => { location.hash = "#" + path; };
const navButton = (label, path) => h("a", { class: "nav-button", href: "#" + path, text: label });

/**
 * En-tête des vues PHP : boutons S'inscrire / Se connecter, ou Se déconnecter + Bienvenue.
 * `page` : la page affichée, dont le bouton est caché (pas de « S'inscrire » sur l'inscription).
 */
function header({ welcome = true, page } = {}) {
    const title = h("a", { class: "titre", href: "#/" }, h("h1", { text: "Catbook" }));
    if (!state.pseudo) {
        const buttons = h("div", { class: "main-buttons" });
        if (page !== "register") buttons.append(navButton("S'inscrire", "/register"));
        if (page !== "login") buttons.append(navButton("Se connecter", "/login"));
        return [h("div", { class: "barNav" }, title, buttons)];
    }
    return [
        h("div", { class: "barNav" }, title, h("a", { href: "#/logout", text: "Se déconnecter" })),
        welcome ? h("h2", { class: "welcome", text: `Bienvenue ${state.pseudo}` }) : null
    ];
}

function errorBox(errors) {
    if (!errors.length) return null;
    return h("div", { class: "errors" }, h("ul", {}, ...errors.map(e => h("li", { style: "color: red;", text: e }))));
}

function show(title, headerParts, ...content) {
    document.title = `${title} - Catbook (démo)`;
    document.querySelector("#header").replaceChildren(...headerParts.filter(Boolean));
    document.querySelector("#page").replaceChildren(...content.filter(Boolean));
    window.scrollTo(0, 0);
}

// ---------- Accueil (MainController + views/main.php) ----------
function formatDay(iso) {
    return new Date(iso).toLocaleDateString("fr-FR", { day: "2-digit", month: "long", year: "numeric" });
}

function formatTime(iso) {
    return new Date(iso).toLocaleTimeString("fr-FR", { hour: "2-digit", minute: "2-digit" });
}

function mainPage() {
    const main = h("main");
    if (!state.pseudo) {
        main.append(h("h2", { text: "Liste des posts" }), h("p", { text: "Les posts ne sont pas visibles hors connexion." }));
        return show("Accueil", header(), main);
    }

    main.append(
        h("button", { class: "create-button", type: "button", text: "Créer un post", onclick: () => go("/post") }),
        h("h2", { text: "Liste des posts" })
    );
    const posts = [...state.posts].sort((a, b) => b.id - a.id);
    if (!posts.length) main.append(h("p", { text: "Aucun post pour l'instant." }));

    let lastDay = null;
    for (const post of posts) {
        const day = post.created_at.slice(0, 10);
        if (day !== lastDay) {
            lastDay = day;
            main.append(h("h2", { class: "date", text: formatDay(post.created_at) }));
        }
        const box = h("div", { class: "posts" },
            h("p", {}, h("strong", { text: post.author }), ` ${formatTime(post.created_at)}`),
            h("h3", { text: post.title }),
            h("p", { class: "content", text: post.content })
        );
        // Seul l'auteur voit les boutons modifier / supprimer
        if (post.author === state.pseudo) {
            box.append(h("div", { class: "button-container" },
                h("button", { class: "edit-btn", type: "button", onclick: () => go(`/edit/${post.id}`) },
                    h("img", { src: "./assets/images/pencil.svg", alt: "modifier", class: "edit-icon" })),
                h("button", { type: "button", onclick: () => deletePost(post.id) },
                    h("img", { src: "./assets/images/bin.svg", alt: "supprimer" }))
            ));
        }
        main.append(box);
    }
    show("Accueil", header(), main);
}

// ---------- Inscription (RegisterController + views/register.php) ----------
function registerPage(errors = [], values = {}) {
    const form = h("form", { class: "form" },
        h("label", { for: "pseudo", text: "Pseudo" }),
        h("input", { type: "text", name: "pseudo", id: "pseudo" }),
        h("label", { for: "password", text: "Mot de passe" }),
        h("input", { type: "password", name: "password", id: "password" }),
        h("label", { for: "email", text: "Email" }),
        h("input", { type: "email", name: "email", id: "email" }),
        h("button", { text: "S'inscrire" })
    );
    form.pseudo.value = values.pseudo || "";
    form.email.value = values.email || "";
    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const pseudo = form.pseudo.value;
        const password = form.password.value;
        const email = form.email.value;
        const errs = [];

        if (!pseudo || !password || !email) errs.push("Tous les champs sont requis.");
        if (!/^[a-zA-Z]{1,20}[0-9]{0,3}$/.test(pseudo)) errs.push("Le pseudo est invalide (lettres + 0 à 3 chiffres max, 20 caractères max).");
        if (!/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/.test(password)) {
            errs.push("Le mot de passe doit contenir au moins 8 caractères, avec majuscule, minuscule, chiffre et caractère spécial.");
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errs.push("L'adresse email n'est pas valide.");
        if (!errs.length && [...state.users, ...EXAMPLE.posts.map(p => ({ pseudo: p.author }))].some(u => u.pseudo.toLowerCase() === pseudo.toLowerCase())) {
            errs.push("Ce pseudo est déjà pris.");
        }
        if (errs.length) return registerPage(errs, { pseudo, email });

        state.users.push({ pseudo, email, password: await fingerprint(password) });
        save();
        go("/login");
    });
    show("Inscription", header({ page: "register" }), h("h1", { text: "Inscription" }), form, errorBox(errors));
}

// ---------- Connexion (LoginController + views/login.php) ----------
function loginPage(errors = []) {
    const form = h("form", { class: "form" },
        h("label", { for: "pseudo", text: "Pseudo" }),
        h("input", { type: "text", name: "pseudo", id: "pseudo" }),
        h("label", { for: "password", text: "Mot de passe" }),
        h("input", { type: "password", name: "password", id: "password" }),
        h("button", { text: "Connexion" }),
        errorBox(errors)
    );
    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const user = state.users.find(u => u.pseudo.toLowerCase() === form.pseudo.value.toLowerCase());
        if (user && user.password === await fingerprint(form.password.value)) {
            state.pseudo = user.pseudo;
            save();
            return go("/");
        }
        loginPage(["Pseudo ou mot de passe incorrect."]);
    });
    show("Connexion", header({ page: "login" }), form);
}

// ---------- Créer un post (PostController + views/post.php) ----------
function postPage(errors = [], values = {}) {
    if (!state.pseudo) {
        return show("Créer un post", header(), h("main", {}, h("p", { style: "color:red; text-align:center;", text: "Vous devez être connecté pour créer un post." })));
    }
    const form = postForm("Publier", values);
    form.append(h("a", { href: "#/", class: "btn-cancel", text: "Annuler" }));
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        const title = form.new_post_title.value.trim();
        const content = form.new_post_content.value.trim();
        if (!title || !content) return postPage(["Tous les champs sont requis."], { title, content });

        const id = Math.max(0, ...state.posts.map(p => p.id)) + 1;
        state.posts.push({ id, author: state.pseudo, title, content, created_at: new Date().toISOString() });
        save();
        go("/");
    });
    show("Créer un post", header(), h("main", {}, h("h2", { text: "Créer un post" }), form, errorBox(errors)));
}

function postForm(buttonLabel, values) {
    const form = h("form", { class: "form" },
        h("label", { for: "new_post_title", text: "Titre" }),
        h("input", { type: "text", name: "new_post_title", id: "new_post_title", required: "" }),
        h("label", { for: "new_post_content", text: "Contenu" }),
        h("textarea", { name: "new_post_content", id: "new_post_content", required: "" }),
        h("button", { type: "submit", text: buttonLabel })
    );
    form.new_post_title.value = values.title || "";
    form.new_post_content.value = values.content || "";
    return form;
}

// ---------- Modifier un post (EditController + views/edit.php) ----------
function editPage(id, errors = []) {
    if (!state.pseudo) return go("/login");
    const post = state.posts.find(p => p.id === id);
    const main = h("main", {}, errorBox(errors));

    // Seul l'auteur peut modifier son post
    if (!post || post.author !== state.pseudo) {
        main.append(h("p", { text: "Le post n'a pas été trouvé ou vous n'êtes pas l'auteur." }));
        return show("Modifier le post", header({ welcome: false }), main);
    }
    const form = postForm("Confirmer", post);
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        const title = form.new_post_title.value.trim();
        const content = form.new_post_content.value.trim();
        if (!title || !content) return editPage(id, ["Le titre et le contenu ne peuvent pas être vides."]);
        Object.assign(post, { title, content });
        save();
        go("/");
    });
    main.append(h("h2", { text: "Modifier le post" }), form);
    show("Modifier le post", header({ welcome: false }), main);
}

// ---------- Supprimer (DeleteController) ----------
function deletePost(id) {
    const post = state.posts.find(p => p.id === id);
    if (post && post.author === state.pseudo) {
        state.posts = state.posts.filter(p => p.id !== id);
        save();
    }
    mainPage();
}

// ---------- Routeur (core/Router.php) ----------
function route() {
    const path = location.hash.replace(/^#/, "") || "/";
    const [, name = "", param] = path.split("/");
    switch (name) {
        case "": return mainPage();
        case "login": return loginPage();
        case "register": return registerPage();
        case "post": return postPage();
        case "edit": return editPage(Number(param));
        case "logout":
            state.pseudo = null;
            save();
            return go("/");
        default:
            show("Page non trouvée", header({ welcome: false }), h("div", {}, h("h3", { text: "Page non trouvée" })));
    }
}

window.addEventListener("hashchange", route);
route();
