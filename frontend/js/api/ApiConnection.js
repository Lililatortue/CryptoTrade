

class ApiConnection{
    xmlRequest;
    BasePath="http://Cryptotrade.local.lan/backend/routing/routing.php";
    endpoint;
    constructor(route){
        this.endpoint = route;
        this.xmlRequest = new XMLHttpRequest();
    };

    getRequest(callback, headers = {}) { 
        this.xmlRequest.open("GET", this.BasePath + this.endpoint);
        this.xmlRequest.setRequestHeader("Content-Type", "application/json");
        
        for (const key in headers) {
            this.xmlRequest.setRequestHeader(key, headers[key]);
        }

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


    postRequest(data, callback,headers = {}) {
        this.xmlRequest.open("POST", this.BasePath + this.endpoint);
        this.xmlRequest.setRequestHeader("Content-Type", "application/json");
    
        for (const key in headers) {
            this.xmlRequest.setRequestHeader(key, headers[key]);
        }

        this.xmlRequest.onload = () => {
            let responseText = this.xmlRequest.responseText;
            if (this.xmlRequest.status >= 200 && this.xmlRequest.status < 300) { 
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

}
