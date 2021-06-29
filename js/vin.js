let vin = document.querySelector('#vin');
let submitButton = document.querySelector('#sbmt-btn');
let responseContainer = document.querySelector('#res');

submitButton.addEventListener('click', (e) => {
    e.preventDefault();
    fetch(
        'https://vpic.nhtsa.dot.gov/api/vehicles/decodevin/' +
            vin.value +
            '?format=json'
    ).then((res) => {
        res.json().then((data) => {
            let html = '';
            html = "<div class='response'>";
            console.log(data.Results);
            html += `<img class='carImg' src='../images/${data.Results[6].Value}.png'>`;
            html += `<p class='vin'>Vin : <b>${vin.value}</b></p>`;
            html += "<div class='structure'>";
            html += `<p>Year : ${data.Results[9].Value}</p>`;
            html += `<p>Make : ${data.Results[6].Value}</p>`;
            html += `<p>Model : ${data.Results[8].Value}</p>`;
            html += `<p>Series : ${data.Results[11].Value}</p>`;
            html += `<p>trim : ${data.Results[12].Value}</p>`;
            html += `<p>type : ${data.Results[13].Value}</p>`;
            html += `<p>body : ${data.Results[23].Value}</p>`;
            html += `<p>trans : ${data.Results[49].Value}</p>`;
            html += `<p>Cylinders : ${data.Results[70].Value}</p>`;
            html += `<p>Fuel : ${data.Results[77].Value}</p>`;
            html += '</div></div>';
            console.log(html);
            responseContainer.innerHTML = html;
        });
    });
});
