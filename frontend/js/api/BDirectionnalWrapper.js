

class BiDirectionnalCommunication extends ApiConnection{

    interval;
    constructor(api,interval){
        super(api.endpoint);
        this.interval =interval;
    }

    getRequest(callback) { 
        const call = ()=> {
            super.getRequest(callback);
            setTimeout(call, this.interval)
        }
        call(); 
    };

    postRequest(data, callback) {
        const call = ()=> {
            super.postRequest(data, callback);
            setTimeout(call, this.interval)
        }
        call(); 
    }


}