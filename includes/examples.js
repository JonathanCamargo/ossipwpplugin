<script  type="text/javascript" src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<div id="myDiv"><!-- Plotly chart will be drawn inside this DIV --></div>
<div id="myDiv2"><!-- Plotly chart will be drawn inside this DIV --></div>
<script type="text/javascript">
var trace1 = {
  x: [1, 2, 3, 4], 
  y: [10, 15, 13, 17], 
  type: 'scatter'
};
var trace2 = {
  x: [1, 2, 3, 4], 
  y: [16, 5, 11, 9], 
  type: 'scatter'
};
var data = [trace1, trace2];
Plotly.newPlot('myDiv', data);
</script>

<p id="p1">Hello World!</p>

<p id="p2">Example from table using API</p>


<script type="text/javascript">
var requestURL ="http://epic2017.localhost/wp-json/applugin/v1/test";
document.getElementById("p1").innerHTML = requestURL;
var request = new XMLHttpRequest();
request.open('GET', requestURL);
request.responseType = 'json';
console.log('hola');
request.setRequestHeader("Content-type", "application/json");
request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
        var json = request.response;
        console.log(json.cost + ", " + json.param);
    }
};
request.send();
</script>


<script type="text/javascript">
var requestURL ="http://epic2017.localhost/wp-json/applugin/v1/history";
document.getElementById("p1").innerHTML = requestURL;
var request = new XMLHttpRequest();
request.open('GET', requestURL);
request.responseType = 'json';
console.log('hola');
request.setRequestHeader("Content-type", "application/json");
request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
        var json = request.response;
        //console.log(json.cost + ", " + json.param);
        console.log(json);
        x_=[];
        y_=[];
        for(var i = 0; i < json.length; i++) {
            var obj = json[i];
            console.log(obj.cost + ", " + obj.param);
            x_[i]=i;
            y_[i]=obj.cost;
        }
        var trace1 = {
            x: x_, 
            y: y_, 
            name: 'Optimization cost',
            type: 'scatter'
        };
          var layout = {
          title: 'Results',
          xaxis: {
            title: 'Number of iterations',
            titlefont: {
              family: 'Courier New, monospace',
              size: 18,
              color: '#7f7f7f'
          }
          },
          yaxis: {
              title: 'Optimization cost',
              titlefont: {
              family: 'Courier New, monospace',
              size: 18,
              color: '#7f7f7f'
          }
          }
        };
        var data = [trace1];
        Plotly.newPlot('myDiv2', data,layout);
//Transform to a js library in the plugin
//Use timer to refresh plot on 10s intervals 

    }
};
request.send();
</script>

&nbsp;



Hola
