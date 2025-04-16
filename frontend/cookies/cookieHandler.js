function setCookie(name, value, hours){
    const encodedValue = encodeURIComponent(value);
    const expires = new Date(Date.now() + hours * 60 * 60 * 1000).toUTCString();
    document.cookie = `${name}=${encodedValue}; expires=${expires}; path=/`;
}

function getCookie (name) {
    const cookies = document.cookie.split('; ');
    const cookie = cookies.find(c => c.startsWith(name + '='));
    if (!cookie) return null;
    const value = decodeURIComponent(cookie.split('=')[1]);
    return value;
}