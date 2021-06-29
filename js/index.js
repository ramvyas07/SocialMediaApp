const apiDiv = document.getElementById('cars');
let carsData = [];

async function getJSONAsync() {
    // The await keyword saves us from having to write a .then() block.
    let json = await axios.get(
        'https://web.njit.edu/~rv8/cs490/test/login/api.php'
    );
    return json;
}

async function main() {
    let html = "<div class='carList'>";
    let rawData = await getJSONAsync();
    const cars = rawData.data;
    for (var i in cars) {
        carsData.push(cars[i]);
    }

    carsData.forEach((car, index) => {
        html += "<div class='car'>";
        html += "<div class='car-details'>";
        html += "<div class='car-image'>";
        html += `<img class='carImage' src='../images/${index}.jpeg' ></div>`;
        html += `<span><b>${car.make}</b></span>`;
        html += `<span> &nbsp;${car.model}</span>`;
        html += `<p>${car.year}</p>`;
        html += `<p>${car.mileage}</p>`;
        html += `<p>${car.color}</p>`;
        html += `<p>${car.location}</p>`;
        html += `<p><b>$${car.price}</b></p>`;

        html += '</div></div>';
    });
    html += '</div>';
    apiDiv.innerHTML = html;
    console.log(html);
}
main();
