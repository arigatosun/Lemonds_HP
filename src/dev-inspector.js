(function () {
  var host = window.location.hostname;
  var isLocal = host === "localhost" || host === "127.0.0.1" || host === "";
  if (!isLocal || window.__lemondsDevInspector) return;
  window.__lemondsDevInspector = true;

  var active = false;
  var hovered = null;
  var selected = null;

  var style = document.createElement("style");
  style.textContent = [
    ".dev-inspector-toggle{position:fixed;right:16px;bottom:16px;z-index:2147483647;border:0;border-radius:999px;background:#111;color:#fff;font:600 12px/1 system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;padding:10px 14px;box-shadow:0 8px 24px rgba(0,0,0,.22);cursor:pointer}",
    ".dev-inspector-toggle.is-active{background:#e83b3b}",
    ".dev-inspector-panel{position:fixed;right:16px;bottom:64px;z-index:2147483647;width:360px;max-width:calc(100vw - 32px);background:#111;color:#fff;border-radius:10px;box-shadow:0 14px 44px rgba(0,0,0,.3);font:12px/1.55 system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;overflow:hidden;display:none}",
    ".dev-inspector-panel.is-visible{display:block}",
    ".dev-inspector-head{display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:#1d1d1d;font-weight:700}",
    ".dev-inspector-close,.dev-inspector-copy{border:0;background:#333;color:#fff;border-radius:6px;padding:6px 9px;font:600 11px/1 system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;cursor:pointer}",
    ".dev-inspector-close{width:28px;height:28px;padding:0}",
    ".dev-inspector-body{padding:10px 12px 12px}",
    ".dev-inspector-row{display:grid;grid-template-columns:72px minmax(0,1fr);gap:10px;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.08)}",
    ".dev-inspector-label{color:#aaa}",
    ".dev-inspector-value{min-width:0;overflow-wrap:anywhere}",
    ".dev-inspector-actions{display:flex;gap:8px;margin-top:10px}",
    ".dev-inspector-hint{margin-top:8px;color:#aaa}",
    ".dev-inspector-hover{outline:2px dashed #2f80ed!important;outline-offset:2px!important}",
    ".dev-inspector-selected{outline:3px solid #e83b3b!important;outline-offset:3px!important}"
  ].join("");
  document.head.appendChild(style);

  var toggle = document.createElement("button");
  toggle.type = "button";
  toggle.className = "dev-inspector-toggle";
  toggle.textContent = "Inspect OFF";
  document.body.appendChild(toggle);

  var panel = document.createElement("aside");
  panel.className = "dev-inspector-panel";
  panel.innerHTML = [
    '<div class="dev-inspector-head">',
    "<span>Element Inspector</span>",
    '<button type="button" class="dev-inspector-close" aria-label="Close">x</button>',
    "</div>",
    '<div class="dev-inspector-body">',
    '<div class="dev-inspector-row"><span class="dev-inspector-label">tag</span><span class="dev-inspector-value" data-field="tag">-</span></div>',
    '<div class="dev-inspector-row"><span class="dev-inspector-label">id</span><span class="dev-inspector-value" data-field="id">-</span></div>',
    '<div class="dev-inspector-row"><span class="dev-inspector-label">class</span><span class="dev-inspector-value" data-field="className">-</span></div>',
    '<div class="dev-inspector-row"><span class="dev-inspector-label">selector</span><span class="dev-inspector-value" data-field="selector">-</span></div>',
    '<div class="dev-inspector-row"><span class="dev-inspector-label">size</span><span class="dev-inspector-value" data-field="size">-</span></div>',
    '<div class="dev-inspector-row"><span class="dev-inspector-label">text</span><span class="dev-inspector-value" data-field="text">-</span></div>',
    '<div class="dev-inspector-actions"><button type="button" class="dev-inspector-copy">Copy selector</button></div>',
    '<div class="dev-inspector-hint">Turn ON, then click an element. Normal links are paused while inspecting.</div>',
    "</div>"
  ].join("");
  document.body.appendChild(panel);

  var fields = {};
  panel.querySelectorAll("[data-field]").forEach(function (node) {
    fields[node.getAttribute("data-field")] = node;
  });

  function isInspectorNode(node) {
    return node && (node === toggle || panel.contains(node) || node.classList && node.classList.contains("dev-inspector-toggle"));
  }

  function cleanToken(value) {
    if (window.CSS && CSS.escape) return CSS.escape(value);
    return String(value).replace(/[^a-zA-Z0-9_-]/g, "\\$&");
  }

  function selectorFor(el) {
    if (!el || el.nodeType !== 1) return "";
    if (el.id) return "#" + cleanToken(el.id);

    var parts = [];
    var node = el;
    while (node && node.nodeType === 1 && node !== document.body) {
      var part = node.tagName.toLowerCase();
      var dataName = node.getAttribute("data-screen-label") || node.getAttribute("data-testid");
      if (dataName) {
        part += '[data-' + (node.hasAttribute("data-testid") ? "testid" : "screen-label") + '="' + dataName.replace(/"/g, '\\"') + '"]';
      } else if (node.classList.length) {
        part += "." + Array.prototype.slice.call(node.classList).slice(0, 3).map(cleanToken).join(".");
      }

      var parent = node.parentElement;
      if (parent) {
        var sameTag = Array.prototype.filter.call(parent.children, function (child) {
          return child.tagName === node.tagName;
        });
        if (sameTag.length > 1 && !node.id && !dataName) {
          part += ":nth-of-type(" + (sameTag.indexOf(node) + 1) + ")";
        }
      }

      parts.unshift(part);
      node = parent;
      if (parts.length >= 5) break;
    }
    return parts.join(" > ");
  }

  function setText(name, value) {
    fields[name].textContent = value || "-";
  }

  function inspect(el) {
    selected && selected.classList.remove("dev-inspector-selected");
    selected = el;
    selected.classList.add("dev-inspector-selected");

    var rect = el.getBoundingClientRect();
    var text = (el.innerText || el.textContent || "").replace(/\s+/g, " ").trim();
    if (text.length > 120) text = text.slice(0, 117) + "...";

    setText("tag", el.tagName.toLowerCase());
    setText("id", el.id);
    setText("className", el.className && typeof el.className === "string" ? el.className : "");
    setText("selector", selectorFor(el));
    setText("size", Math.round(rect.width) + " x " + Math.round(rect.height));
    setText("text", text);
    panel.classList.add("is-visible");
  }

  function setActive(next) {
    active = next;
    toggle.classList.toggle("is-active", active);
    toggle.textContent = active ? "Inspect ON" : "Inspect OFF";
    if (!active) {
      hovered && hovered.classList.remove("dev-inspector-hover");
      hovered = null;
    }
  }

  toggle.addEventListener("click", function () {
    setActive(!active);
  });

  panel.querySelector(".dev-inspector-close").addEventListener("click", function () {
    panel.classList.remove("is-visible");
    selected && selected.classList.remove("dev-inspector-selected");
    selected = null;
  });

  panel.querySelector(".dev-inspector-copy").addEventListener("click", function () {
    var value = fields.selector.textContent;
    if (!value || value === "-") return;
    navigator.clipboard && navigator.clipboard.writeText(value);
  });

  document.addEventListener("mouseover", function (event) {
    if (!active || isInspectorNode(event.target)) return;
    hovered && hovered.classList.remove("dev-inspector-hover");
    hovered = event.target;
    hovered.classList.add("dev-inspector-hover");
  }, true);

  document.addEventListener("mouseout", function (event) {
    if (hovered && event.target === hovered) {
      hovered.classList.remove("dev-inspector-hover");
      hovered = null;
    }
  }, true);

  document.addEventListener("click", function (event) {
    if (!active || isInspectorNode(event.target)) return;
    event.preventDefault();
    event.stopPropagation();
    inspect(event.target);
  }, true);

  document.addEventListener("keydown", function (event) {
    if (event.altKey && event.key.toLowerCase() === "i") {
      event.preventDefault();
      setActive(!active);
    }
  });
})();
