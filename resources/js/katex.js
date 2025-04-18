import "katex/dist/katex.min.css";
import katex from "katex";
import renderMathInElement from "katex/dist/contrib/auto-render.js";

window.katex = katex;
window.renderMathInElement = renderMathInElement;

document.addEventListener("DOMContentLoaded", function () {
    renderMathInElement(document.body, {
        delimiters: [
            { left: "$$", right: "$$", display: true },
            { left: "$", right: "$", display: false },
            { left: "\\(", right: "\\)", display: false },
            { left: "\\[", right: "\\]", display: true },
        ],
        throwOnError: false,
    });
});
