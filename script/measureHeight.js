// measureHeight.js
const puppeteer = require("puppeteer");

async function measureHeight(htmlContent) {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();

    // Set the content of the page to the HTML you provided
    await page.setContent(htmlContent);

    // Measure the height of the content
    const height = await page.evaluate(() => {
        const body = document.querySelector("body");
        return body.scrollHeight;
    });

    await browser.close();
    return height;
}

// Read input from command line and output height
(async () => {
    const htmlContent = process.argv[2];
    const height = await measureHeight(htmlContent);
    console.log(height);
})();
