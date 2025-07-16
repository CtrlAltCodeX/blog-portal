window.AsinFetcher = {
    fetchAndFill: function (options) {
        const { asinInputId, token } = options;

        const asinInput = document.getElementById(asinInputId);
        if (!asinInput || !asinInput.value.trim()) {
            alert('❌ Please enter ASIN number(s).');
            return;
        }

        const asins = asinInput.value.trim().split(',').map(a => a.trim()).filter(Boolean);

        fetch('https://api.exam360shop.com/api/asin-scraper', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({ asins })
        })
        .then(res => res.json())
        .then(data => {
            const result = data[0] || {};

            if (document.getElementById('title')) {
                document.getElementById('title').value = result.Title || '';
            }
            if (document.getElementById('desc')) {
                document.getElementById('desc').value = result.Discription || '';
            }
            if (document.getElementById('publisher')) {
                document.getElementById('publisher').value = result.Publisher || '';
            }
            if (document.getElementById('mrp')) {
                document.getElementById('mrp').value = result.MRP || '';
            }
        

            // console.log('Data Filled:', result);
        })
        .catch(err => {
            alert('❌ Error: ' + err.message);
            console.error(err);
        });
    }
};
