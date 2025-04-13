class ServerErrorHandling{
    errorlist;
    constructor(errorlist){
        this.errorlist = errorlist;
    };
    /**
     * refresh errors
     */
    refresh(){
        document.querySelectorAll(".input-field").forEach(input => { // remove visbible red border
            input.classList.remove("input-error");
          });
          document.querySelectorAll(".error-msg").forEach(msg => {
            msg.innerText = "";
          });
    }

    /**
     * Prends les elements de error-css.css et 
     * les applique sur les div qui ont class = "input-error". fonctionne pas
     * pass a travers les input fields et lui rajoute la css class input-error
     * @param {[]} error //list d'erreur renvoyer par le serveur web
     */
    handleError(error){
        for(const field in error) {
            const input = document.getElementById(field);
            const errorMsg = document.getElementById(`${field}-error`);
      
            if (input) input.classList.add("input-error");//rajoute
        
            if (errorMsg && this.errorlist[field]) {
              errorMsg.innerText = this.errorlist[field];
            }
        }
    }
}
