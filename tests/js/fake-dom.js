/**
 * Fake DOM minimal (tanpa dependency) untuk unit test engine auto-save draft
 * Freelancer: `tests/js/form-draft-autosave.test.js`.
 *
 * Hanya mengimplementasikan bagian DOM yang benar-benar dipakai oleh
 * `public/js/form-draft-autosave.js`:
 *   - elemen dengan getAttribute/hasAttribute/setAttribute/matches
 *   - querySelector(All) untuk selector sederhana: "tag", "[attr]", "[attr=val]",
 *     "#id", dan gabungan yang dipisah koma (mis. "input, select, textarea")
 *   - document.querySelectorAll / createElement / body
 *   - window.localStorage (Map), window.Event, window.location
 *
 * Dibuat manual (bukan jsdom) karena project ini tidak memakai jsdom sehingga
 * test bisa jalan lewat `node --test tests/js` tanpa `npm install`.
 */

class FakeEvent {
    constructor(type, init) {
        this.type = String(type);
        this.bubbles = !!(init && init.bubbles);
        this.defaultPrevented = false;
        this.target = null;
    }
}

function matchesSimple(node, selector) {
    let rest = selector;

    const tagMatch = /^[A-Za-z][\w-]*/.exec(rest);
    if (tagMatch) {
        if (String(node.tagName).toUpperCase() !== tagMatch[0].toUpperCase()) return false;
        rest = rest.slice(tagMatch[0].length);
    }

    const attrRe = /\[([^\]]+)\]/g;
    let m;
    while ((m = attrRe.exec(rest)) !== null) {
        const eq = m[1].indexOf('=');
        const name = (eq === -1 ? m[1] : m[1].slice(0, eq)).trim();
        if (!node.hasAttribute(name)) return false;
        if (eq !== -1) {
            const expected = m[1].slice(eq + 1).trim().replace(/^["']|["']$/g, '');
            if (node.getAttribute(name) !== expected) return false;
        }
    }

    const idMatch = /#([\w-]+)/.exec(rest);
    if (idMatch && node.getAttribute('id') !== idMatch[1]) return false;

    return true;
}

function matches(node, selector) {
    if (!node || typeof node.tagName !== 'string') return false;
    return String(selector)
        .split(',')
        .map((part) => part.trim())
        .filter(Boolean)
        .some((part) => matchesSimple(node, part));
}

function descendants(node, out) {
    const children = node.children || [];
    for (let i = 0; i < children.length; i++) {
        out.push(children[i]);
        descendants(children[i], out);
    }
    return out;
}

function queryAll(root, selector) {
    const all = descendants(root, []);
    const out = [];
    for (let i = 0; i < all.length; i++) {
        if (matches(all[i], selector)) out.push(all[i]);
    }

    return out;
}

export function createElement(tagName) {
    const node = {
        tagName: String(tagName).toUpperCase(),
        attributes: Object.create(null),
        children: [],
        parentNode: null,
        style: { cssText: '' },
        value: '',
        checked: false,
        disabled: false,
        multiple: false,
        selected: false,
        options: null,
        textContent: '',
        title: '',
        type: '',
        name: '',
        id: '',

        getAttribute(name) {
            return Object.prototype.hasOwnProperty.call(node.attributes, name)
                ? node.attributes[name]
                : null;
        },
        setAttribute(name, value) {
            node.attributes[name] = String(value);
            if (name === 'type') node.type = String(value);
            if (name === 'name') node.name = String(value);
            if (name === 'id') node.id = String(value);
        },
        removeAttribute(name) {
            delete node.attributes[name];
        },
        hasAttribute(name) {
            return Object.prototype.hasOwnProperty.call(node.attributes, name);
        },
        matches(selector) {
            return matches(node, selector);
        },
        querySelector(selector) {
            return queryAll(node, selector)[0] || null;
        },
        querySelectorAll(selector) {
            return queryAll(node, selector);
        },
        appendChild(child) {
            child.parentNode = node;
            node.children.push(child);
            return child;
        },
        removeChild(child) {
            const i = node.children.indexOf(child);
            if (i >= 0) {
                node.children.splice(i, 1);
                child.parentNode = null;
            }
            return child;
        },
        addEventListener(type, handler) {
            const t = String(type);
            (node.__listeners[t] = node.__listeners[t] || []).push(handler);
        },
        removeEventListener() {},
        dispatchEvent() {
            return true;
        },
        __listeners: Object.create(null)
    };

    if (node.tagName === 'INPUT') node.type = 'text';

    return node;
}

/** Picu event tiruan pada elemen (handler yang didaftarkan engine). */
export function fire(node, type, event = {}) {
    const handlers = (node.__listeners && node.__listeners[type]) || [];
    const ev = Object.assign({ type, bubbles: true, target: node }, event);
    for (let i = 0; i < handlers.length; i++) handlers[i].call(node, ev);
    return ev;
}

/** Elemen dengan opsi ringkas: el('input', { attrs: {...}, props: {...}, parent }). */
export function el(tagName, options = {}) {
    const node = createElement(tagName);
    if (options.attrs) {
        Object.keys(options.attrs).forEach((name) => node.setAttribute(name, options.attrs[name]));
    }
    if (options.props) Object.assign(node, options.props);
    if (options.parent) options.parent.appendChild(node);
    return node;
}

/** localStorage tiruan (Map) + opsi untuk mensimulasikan storage diblokir. */
export function createStorage(options = {}) {
    const map = new Map();

    return {
        getItem(key) {
            if (options.throwOnGet) throw new Error('storage blocked');
            const k = String(key);
            return map.has(k) ? map.get(k) : null;
        },
        setItem(key, value) {
            if (options.throwOnSet) throw new Error('storage blocked');
            map.set(String(key), String(value));
        },
        removeItem(key) {
            if (options.throwOnRemove) throw new Error('storage blocked');
            map.delete(String(key));
        },
        clear() {
            map.clear();
        },
        keys() {
            return Array.from(map.keys());
        },
        raw() {
            return map;
        }
    };
}


/** document + window tiruan yang dipakai engine. */
export function createDom(options = {}) {
    const doc = createElement('#document');
    doc.readyState = 'complete';
    doc.visibilityState = 'visible';

    const body = el('body', { parent: doc });

    const pathname = options.pathname || '/freelancer/penawaran/create/1';
    const listeners = {};

    const documentApi = {
        body,
        readyState: 'complete',
        visibilityState: 'visible',
        createElement(tagName) {
            return createElement(tagName);
        },
        querySelector(selector) {
            return queryAll(doc, selector)[0] || null;
        },
        querySelectorAll(selector) {
            return queryAll(doc, selector);
        },
        getElementById(id) {
            return queryAll(doc, '#' + id)[0] || null;
        },
        addEventListener(type, handler) {
            (listeners['doc:' + type] = listeners['doc:' + type] || []).push(handler);
        },
        removeEventListener() {}
    };

    const windowApi = {
        document: documentApi,
        localStorage: options.storage === undefined ? createStorage() : options.storage,
        location: { pathname, href: 'http://localhost' + pathname },
        Event: FakeEvent,
        addEventListener(type, handler) {
            (listeners['win:' + type] = listeners['win:' + type] || []).push(handler);
        },
        removeEventListener() {},
        dispatchEvent() {
            return true;
        },
        __listeners: listeners
    };

    return { document: documentApi, body, window: windowApi, listeners };
}
