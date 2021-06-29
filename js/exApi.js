const API = 'https://private-7c03b7-carsapi1.apiary-mock.com/manufacturers';
const apiDiv = document.getElementById('cars');
let carsData = [];

async function getJSONAsync() {
    // The await keyword saves us from having to write a .then() block.
    let json = await axios.get(API);
    console.log(json);
    return json;
}

async function main() {
    let html = "<div class='carList'>";
    let rawData = await getJSONAsync();
    const cars = rawData.data;
    for (var i in cars) {
        carsData.push(cars[i]);
    }

    console.log('carsData', carsData);

    carsData.forEach((car, index) => {
        html += "<div class='car'>";
        html += "<div class='car-details'>";
        html += `<p id='id'><b>${car.id}</b></p>`;
        html += `<p id='name'> ${car.name.toUpperCase()}</p>`;
        html += `<p id='hp'><b>Avg Horsepower :</b> ${car.avg_horsepower.toFixed(
            2
        )}</p>`;
        html += `<p id='price'> <b>Avg Price :</b> ${car.avg_price.toFixed(
            2
        )}</p>`;
        html += '</div></div>';
    });
    html += '</div>';
    apiDiv.innerHTML = html;
    console.log(html);
}
main();
