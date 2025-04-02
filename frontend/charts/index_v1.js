//nb de bar qui s'affiche dans le BG (lignes grises)
let barCount = 60;
//stoque la date d'auj dans une variable
let initDate = new Date().toUTCString();
//ctx = 'Context' pour canvas details dans HTML
const ctx = document.getElementById('chart').getContext('2d');
ctx.canvas.width = 1000;
ctx.canvas.height = 250;

//Init des arrays qui contient le DATA pour chaque version du graph (2 en total ici)
//Pour la version candle (contient o, h, l & c {opening, high, low & closing})
let barData = new Array(barCount);
//Pour la version ligne (contient seulement closing en points)
let lineData = new Array(barCount);

getRandomData(initDate); //<< TODO: A CHANGER

//CONFIG BLOCK (always placed before the render block)
const chart = new Chart(ctx, {
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
        hidden: true, //a l'ouvrage de la page, elle est 'hidden'
      }
    ]
  }
});

//Genere un nb random sans depacer le Max & Min. La formule sert a faire des FLUCTUATIONS 
function randomNumber(min, max) { return Math.random() * (max - min) + min; }

function randomBar(target, index, date, lastClose) {
  var open = +randomNumber(lastClose * 0.95, lastClose * 1.05).toFixed(2); //+-5% variation sur le 'LastClose'
  var close = +randomNumber(open * 0.95, open * 1.05).toFixed(2); //+-5% variation sur 'open'
  var high = +randomNumber(Math.max(open, close), Math.max(open, close) * 1.1).toFixed(2); //+-10% variation high entre open & close
  var low = +randomNumber(Math.min(open, close) * 0.9, Math.min(open, close)).toFixed(2); //+-10% variation low entre open & close

  //creer target si n'exte pas 
  if (!target[index]) { target[index] = {}; }
  //barData[] stored => target[index] / index = jour qui represente (day 1, day 2...)
  //'.assign' update le data sans le remplacer
  Object.assign(target[index], 
  {
    x: date.valueOf(), //'valueOf' convertie date en timestamp pour Chart.js
    //values for 'y' axis
    o: open, // when market opens (9h30am normalement). Commence avec une valeur
    h: high, //PEAK du stock
    l: low, //Lowest point du stock
    c: close //closing (4pm normalement). Finalisation du market
  });
}
// //EXAMPLE de output 
// {
//   x: 1711976400000,    // Timestamp
//   o: 29.56 $,          // Open price
//   h: 31.23 $,          // High price
//   l: 28.94 $,          // Low price
//   c: 30.12 $           // Close price
// }
// //lineData only stores { x: timestamp, y: close_price }.

function getRandomData(dateStr) {
  var date = luxon.DateTime.fromRFC2822(dateStr); //Utilise LUXON Adapter : https://www.chartjs.org/chartjs-chart-financial/ 
  let maxDate = date.plus({ months: 3 }); //maximum de 3 mois pour le 'x' axis
  //gestion de semaines
  for (let i = 0; i < barData.length;) 
  {
    date = date.plus({days: 1});
        // Stop ici (si excede mois maximum)
        if (date > maxDate) { break; }
    // //crypto continue les fin de semaines, alors pas besoin de se 'if'
    // if (date.weekday <= 5) 
    // {
      randomBar(barData, i, date, i == 0 ? 30 : barData[i - 1].c);
      lineData[i] = {x: barData[i].x, y: barData[i].c};
      i++;
    // }
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

// Instantly assign Chart.js version
[...document.getElementsByTagName('select')].forEach(element => element.addEventListener('change', update));

document.getElementById('randomizeData').addEventListener('click', function() {
  getRandomData(initDate, barData);
  chart.update(); // Use this instead of just 'update()'
});
