$(document).ready(function () {
    let selectedProducts = [];

    function renderProductCard(product) {
        return `
            <div class="col-md-4 mb-4">
                <div class="card ${selectedProducts.find(p => p.id === product.id) ? 'selected' : ''}" data-id="${product.id}">
                    <img src="${product.image}" class="card-img-top" alt="${product.productnameenglish}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${product.productnameenglish}</h5>
                        <p class="card-text">${product.description}</p>
                        <p class="mt-auto">SR ${product.price}</p>
                    </div>
                </div>
            </div>
        `;
    }

    function renderSelectedProductCard(product) {
        return `
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="${product.image}" class="card-img-top" alt="${product.productnameenglish}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${product.productnameenglish}</h5>
                        <p class="card-text">${product.description}</p>
                        <p class="mt-auto">SR ${product.price}</p>
                    </div>
                </div>
            </div>
        `;
    }

    function updateSelectedProducts() {
        const selectedProductList = $('#selected-products');
        selectedProductList.html(selectedProducts.map(product => renderSelectedProductCard(product)).join(''));
    }

    function enableTab(step) {
        const stepTabs = $('.nav-link');
        stepTabs.each(function (index) {
            if (index < step) {
                $(this).removeClass('active').addClass('disabled');
            } else if (index === step - 1) {
                $(this).addClass('active').removeClass('disabled');
            } else {
                $(this).removeClass('active').removeClass('disabled');
            }
        });
        $('#step' + step + '-tab').tab('show');
    }

    $('#search').on('input', function () {
        const searchQuery = $(this).val().toLowerCase();
        $.ajax({
            url: '/search/products',
            method: 'GET',
            data: { query: searchQuery },
            success: function (response) {
                const productList = $('#product-list');
                productList.html(response.map(product => renderProductCard(product)).join(''));
            }
        });
    });

    $('#product-list').on('click', '.card', function () {
        const card = $(this);
        const productId = parseInt(card.data('id'));
        const product = selectedProducts.find(p => p.id === productId);

        if (product) {
            selectedProducts = selectedProducts.filter(p => p.id !== productId);
        } else {
            const newProduct = {
                id: productId,
                productnameenglish: card.find('.card-title').text(),
                description: card.find('.card-text').text(),
                image: card.find('img').attr('src'),
                price: card.find('.mt-auto').text().replace('SR ', '')
            };
            selectedProducts.push(newProduct);
        }
        card.toggleClass('selected');
    });

    $('#go-to-step2').on('click', function () {
        updateSelectedProducts();
        enableTab(2);
    });

    $('#go-to-step3').on('click', function () {
        enableTab(3);
    });

    $('#back-to-step1').on('click', function () {
        enableTab(1);
    });

    $('#back-to-step2').on('click', function () {
        enableTab(2);
    });
});
