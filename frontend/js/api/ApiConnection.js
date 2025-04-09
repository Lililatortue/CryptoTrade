

class ApiConnection{
    xmlRequest;
    fullroute;
    constructor(route){
        this.fullroute="http://Cryptotrade.local.lan/backend/routing/MacroRouting.php"+route;
        this.xmlRequest = new XMLHttpRequest();
    };

    getRequest(callback) { 
        this.xmlRequest.open("GET", this.fullroute);
        this.xmlRequest.setRequestHeader("Content-Type", "application/json");
        this.xmlRequest.onload = () => {
            if (this.xmlRequest.status >= 200 && this.xmlRequest.status < 300) {
                
                callback(null, this.xmlRequest.response);
            } else {
                
                callback(this.xmlRequest.status, null);
            }
        };

        this.xmlRequest.onerror = () => {
            
            callback(this.statusText, null);
        };
        this.xmlRequest.send(); 
    }

    postRequest(data,callback) {
        this.xmlRequest.open("POST", this.fullroute);
        this.xmlRequest.setRequestHeader("Content-Type", "application/json");
        this.xmlRequest.onload = () => {
            if (this.xmlRequest.status >= 200 && this.xmlRequest.status < 300) {

                callback(null, this.xmlRequest.response);
            } else {
                
                callback(this.xmlRequest.statusText, null);
            }
        };

        this.xmlRequest.onerror = () => {
            
            callback(this.xmlRequest.statusText, null);
        };
        this.xmlRequest.send(JSON.stringify(data)); 
    }
}
