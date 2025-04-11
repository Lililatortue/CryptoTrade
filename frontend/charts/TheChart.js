//[*X-AXIS*] FIXED time range: from 5:00 AM to 5:00 PM, apres ca ferme
const startOfDay = new Date();
startOfDay.setHours(5, 0, 0, 0); // 5:00 AM
const endOfDay = new Date();
endOfDay.setHours(17, 0, 0, 0); // 5:00 PM

//ctx = 'Context' pour canvas details dans HTML
const ctx = document.getElementById('chart').getContext('2d');
ctx.canvas.width = 1000;
ctx.canvas.height = 250;

//Init des arrays qui contient le DATA pour chaque version du graph (2 en total ici)
//Pour la version candle (contient o, h, l & c {opening, high, low & closing})
let barData = [];
//Pour la version ligne (contient seulement closing en points)
let lineData = [];

//getRandomData(initDate); //<< TODO: A CHANGER

//CONFIG BLOCK (always placed before the render block)

//construction du chart
const chart = new Chart(ctx,   
{
  type: 'candlestick', //type candlestick pour 'FINANCES, STOCKS'
  data: //Tous les données pour mettre dans le graph
  { 
    datasets: [
      {
        label: 'Candle', //Legende
        data: barData, //contient o, h, l & c
      }, 
      {
        label: 'Linear',
        type: 'line',
        data: lineData, //contient c 
        //hidden: true, //a l'ouvrage de la page, elle est 'hidden'
      }
    ]
  }, 
  options: {
    scales: {
      x: {
        type: 'time',
        time: {
          unit: 'hour',
          displayFormats: { hour: 'h a' } // Ex OutPut: 5 AM, 6 AM....
        },
        min: startOfDay.getTime(), // Force graph a commencer a 5:00 AM
        max: endOfDay.getTime(),   // Force graph a finir a 5:00 PM
        ticks: {
          source: 'auto',
          autoSkip: false
        }
      },
      y: {
        type: 'linear',
        beginAtZero: false, // Set to true if you want 0 at bottom
      }
    }
  }
})

function getCryptoData(cryptoJSON) {
  //INIT
  barData.length = 0;
  lineData.length = 0;
  let i = 0; //for the loop
  for (const key in cryptoJSON) { 
    // Récupère chaque valeurs et les convertit en nombre (String en le moment)
    const [o, c, h, l] = cryptoJSON[key].map(parseFloat);

    // Sépare la clé en deux parties : date et heure
    const [datePart, timePart] = key.split('_');
    const [day, month] = datePart.split('-').map(Number);
    const [hour, minute] = timePart.replace('h', ':').split(':').map(Number);

     // Crée une vraie date JS
    const date = new Date();
    date.setMonth(month - 1);
    date.setDate(day);
    date.setHours(hour);
    date.setMinutes(minute);
    date.setSeconds(0, 0);

    // Si l'index n'existe pas encore, crée-le
    if (!barData[i]) barData[i] = {};

     // Stocker les données dans le format attendu
     Object.assign(barData[i], {
      x: date.valueOf(), // Convertit cette date en "timestamp" (necessaire en chart.js)
      o,
      h,
      l,
      c
    });

    // Stocker la valeur "close" pour le graphe en ligne
    lineData[i] = { x: date.valueOf(), y: c };
    i++;
  }
}

//funct qui en lien avec les buttons de changements de graph
let update = function() {
  let dataset = chart.config.data.datasets[0];

  // linear vs log
  let scaleType = document.getElementById('scale-type').value;
  chart.config.options.scales.y.type = scaleType;

  // -- COULEURS -- 
  let colorScheme = document.getElementById('color-scheme').value;
  if (colorScheme == 'neon') {
    chart.config.data.datasets[0].backgroundColors = 
    {
      up: '#01ff01',
      down: '#fe0000',
      unchanged: '#999',
    };
  } 
  else { delete chart.config.data.datasets[0].backgroundColors; }

  // border
  let border = document.getElementById('border').value;
  if (border === 'false') 
  { dataset.borderColors = 'rgba(0, 0, 0, 0)'; } 
  else { delete dataset.borderColors; }

  chart.update();
};
//debug pour les dates
console.log("First candle date:", new Date(barData[0]?.x));
console.log("Chart X range:", startOfDay.toLocaleString(), "→", endOfDay.toLocaleString());

//fonction qui adapte la couleur de la Ligne du Graph
function updateLineColor() {
  let lineDataset = chart.config.data.datasets[1];
  if (lineData[0].y > lineData[lineData.length - 1].y) {
    lineDataset.borderColor = 'red';   
    //changement des pointillés
    lineDataset.pointBackgroundColor = 'pink';
    lineDataset.pointBorderColor = 'pink';
  } 
  else 
  {
    lineDataset.borderColor = 'green';
    //changement des pointillés
    lineDataset.pointBackgroundColor = 'aquamarine';
    lineDataset.pointBorderColor = 'aquamarine';
  }
}

// Instantly assign Chart.js version
[...document.getElementsByTagName('select')].forEach(element => element.addEventListener('change', update));

//GRAPH se load auto a l'ouverture de page
window.addEventListener('DOMContentLoaded', () => {
  getCryptoData(BTC);
  updateLineColor();
  chart.update();
});
