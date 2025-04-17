//(Max/Min)% de volatilité pour les high & low
const maxPerc = 5;  // Maximum change in price (5%, can be negative and positive)

// Fonction qui genere le prochain prix && si profit (positif) ou loss (negatif)
function gen_Price(price) //'gen == 'generate'
{
    const changePerc = Math.random() * maxPerc; //5% ou <
    //formule qui check si sera positif OU negative (+ or -)
    const sign = Math.random() > 0.5 ? 1 : -1; //Math.random genere float entre 0 > et < 1 (50% chance d'etre negative)
    //ex de max : 105 * sign (+ or -) / 100 (pourcentage)
    return price * (1 + (sign * changePerc / 100));
}

// Function qui genere && stock les datas dans le JSON (BTC)
function gen_StockData(initialPrice, maxEntries, initialTime) 
{
    let lastClose = initialPrice;
    // Clone de la date initiale pour ne pas modifier l'objet original
    let updatedDate = new Date(initialTime);

    for (let i = 0; i < maxEntries; i++) //créer jusqu'à temps attends maxEntries
    {
        let o = parseFloat(lastClose).toFixed(2);

        // Generate random closing price
        let c = gen_Price(o).toFixed(2);

        //Si c > o (GREEN), alors high (h) doit etre >= c  && low (l) <= o
        //cas contraire (RED), alors h doit etre >= o  && l <= c
         let h, l;
        //volatilité max de 5% (maxPerc)
         if (c > o) { 
             h = (parseFloat(c) * (1 + Math.random() * (maxPerc / 100))).toFixed(2);
             l = (parseFloat(o) * (1 - Math.random() * (maxPerc / 100))).toFixed(2);
         } else { 
             h = (parseFloat(o) * (1 + Math.random() * (maxPerc / 100))).toFixed(2);
             l = (parseFloat(c) * (1 - Math.random() * (maxPerc / 100))).toFixed(2);
         }

        // Set la clé(key) as `date-time` STRING
        const key = `${updatedDate.getDate()}-${updatedDate.getMonth() + 1}_${updatedDate.getHours()}h${updatedDate.getMinutes()}`;

        // Store le prix dans le JSON
        BTC[key] = [o, c, h, l];
        // Prepare le Entry prochain
        lastClose = c;
        //Prochain PRICE va se generer dans 10 minutes
        updatedDate.setMinutes(updatedDate.getMinutes() + 20); // Prochain point dans 20 minutes
    }
}

// Fonction pour générer la date actuelle + commence à 9h00 AM
function getFirstData() {   
    const beginning = new Date(); //premiere valeur du graph (12h avant time.Now)
    beginning.setHours(5, 0, 0, 0); // 5h00, minutes/secondes/millisecondes = 0
    return beginning;
}

// -- MAIN JSON -- 
//init du prix BTC
const BTC_init_Price = 3000; //Table : crypto => 'valeur'
//init de date auj. a 5AM 
const initTime = getFirstData();

// -- BASE DE DONNÉES simulation --
//premier PK : crypto_name
const BTC = {};

gen_StockData(BTC_init_Price, 36, initTime); //derniere donnee == today

console.log(BTC);