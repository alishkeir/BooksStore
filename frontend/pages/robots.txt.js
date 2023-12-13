import settingsVars from "@vars/settingsVars";
import url from "@libs/url";

function generateTxt(domain) {
    return `User-agent: *
Disallow: /kosar
Disallow: /penztar
Disallow: /penztar/*
Disallow: /profil/szallitasi-adatok
Disallow: /profil/*

User-agent: CCBot
Disallow: /

User-agent: GPTBot
Disallow: /

User-Agent: omgili
Disallow: /

User-Agent: omgilibot
Disallow: /

User-agent: Google-Extended
Disallow: /

Sitemap: ${domain}/sitemap.xml`;
}

function generateDisabledTxt() {
    return `User-agent: *
Disallow: /
`;
}

function RobotsTxt() {
    // getServerSideProps will do the heavy lifting
}

export async function getServerSideProps({ res }) {
    let settings = settingsVars.get(url.getHost());

    let txt = "";
    if(settings.key !== "NAGYKER"){
        txt = generateTxt(settings.common.meta.url);
    }else {
        txt = generateDisabledTxt();
    }
    res.setHeader('Content-Type', 'text/plain');
    // we send the XML to the browser
    res.write(txt);
    res.end();

    return {
        props: {},
    };
}

export default RobotsTxt;
