function setCookie(name, value, hours){
    console.log("starting");
    const json = JSON.stringify(value);
    const encodedjson = encodeURIComponent(json);
    const date = new Date();
    date.setTime(date.getTime() + ( 60 * 60 * 1000));
    document.cookie = name + "=" + encodedjson + ";" + hours + ";path=/";
    console.log("finish");
}

function getCookie (name) {
    const cookies = document.cookie.split('; ');
    const cookie = cookies.find(c => c.startsWith(name + '='));
    if (!cookie) return null;
    const value = decodeURIComponent(cookie.split('=')[1]);
    return JSON.parse(value);
}