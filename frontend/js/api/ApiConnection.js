

class ApiConnection{
    xmlRequest;
    fullroute="http://localhost/CryptoTrade/backend/routing/MacroRouting.php";
    endpoint;
    constructor(route){
        this.endpoint = route;
        this.xmlRequest = new XMLHttpRequest();
    };
    setEndpoint(endpoint) {
        this.endpoint = endpoint;
    }
    getRequest(callback) { 
        this.xmlRequest.open("GET", this.fullroute + this.endpoint);
        this.xmlRequest.setRequestHeader("Content-Type", "application/json");
        this.xmlRequest.onload = () => {
            let responseText = this.xmlRequest.responseText;
            if (this.xmlRequest.status >= 200 && this.xmlRequest.status < 300) {
                const data = JSON.parse(responseText);
                callback(null,data);
            } else {
                const error = JSON.parse(responseText);
                callback(error, null);
            }
        };

        this.xmlRequest.onerror = () => {
            
            callback(this.statusText, null);
        };
        this.xmlRequest.send(); 
    };

    postRequest(data, callback) {
        this.xmlRequest.open("POST", this.fullroute + this.endpoint);
        this.xmlRequest.setRequestHeader("Content-Type", "application/json");
    
        this.xmlRequest.onload = () => {
            let responseText = this.xmlRequest.responseText;
            if (this.xmlRequest.status >= 200 && this.xmlRequest.status < 300) { 

                console.log(responseText);
                const data = JSON.parse(responseText);
                try {
                    callback(null, data);
                    
                } catch (err) {
                    console.log("Callback error :", err);
                }
            } else {
                try {
                    const error = JSON.parse(responseText);
                    callback(error, null);
                   
                } catch (e) {
                    callback(this.xmlRequest.status, null);
                }
            }
        };
        this.xmlRequest.onerror = () => {
            callback("erreur de reseautage", null);
        };
        this.xmlRequest.send(JSON.stringify(data));
    };

    getToken(callback) {
        this.xmlRequest.open("GET", this.fullroute + "/session/validateToken");
        this.xmlRequest.setRequestHeader("Content-Type", "application/json");

        const token = getCookie("Token");
        if (token) {
            this.xmlRequest.setRequestHeader("Authorization",`Bearer ${token}`);
        }
        this.xmlRequest.onload = () => {
            let responseText = this.xmlRequest.responseText;
            if (this.xmlRequest.status >= 200 && this.xmlRequest.status < 300) {
                try {
                    const data = JSON.parse(responseText);
                    callback(null, data);
                    
                } catch (err) {
                    console.log("Callback error :", err);
                }
            } else {
                try {
                    const error = JSON.parse(responseText);
                    callback(error, null);
                   
                } catch (e) {
                    callback(this.xmlRequest.statusText, null);
                }
            }
        };
        this.xmlRequest.onerror = () => {
            callback("erreur de reseautage", null);
        };
        this.xmlRequest.send(JSON.stringify({}));
    };
}
