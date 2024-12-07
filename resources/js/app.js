import * as pdfjsLib from "pdfjs-dist";
require("./bootstrap");

pdfjsLib.GlobalWorkerOptions.workerSrc = `https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js`;

window.pdfjsLib = pdfjsLib;
