;jQuery(function ($) {
    var $refreshBtn = $('.rates__refresh-btn');
    var $addBtn = $('.rates__add-btn');
    var $ratesList = $('.rates__list tbody');
    var $modal = $('.modal');
    var $modalContent = $modal.find('.modal__content');

    var state = {
        rates: []
    };

    updateView();

    $refreshBtn.on('click', function () {
        updateView(true);
    });

    $addBtn.on('click', function () {
        showModal(renderSelectList(state.rates));
    });

    $ratesList.on('click', '.rates__remove-btn', function () {
        toggleRateAppearance(this.dataset.id, false);
    })

    $modal
        .on('click', function () {
            closeModal();
        })
        .find('.modal__content')
            .on('click', function (e) {
                e.stopPropagation();
            })
            .on('click', 'li', function () {
                toggleRateAppearance(this.id, true);
            });

    function updateView(refresh) {
        requestRates(refresh).then(renderRates);
    }

    function toggleRateAppearance(id, appear) {
        var url = CONFIG.baseUrl + '/rates/' + id + '/toggle';

        return $.ajax({
            method: 'PUT',
            url: url,
            dataType: 'json',
            data: {
                data: appear
            }
        })
            .then(function () {
                state.rates = state.rates.map(function (item) {
                    if (item.id === id) {
                        return Object.assign({}, item, { appear: appear });
                    }
                    else {
                        return item;
                    }
                });
                renderRates(state.rates);
            })
            .catch(function () { console.log('failed to update rate', id) });
    }

    function requestRates(refresh) {
        var url = CONFIG.baseUrl + '/rates' + (refresh ? '/refresh' : '');

        return $.ajax({
            url: url,
            dataType: 'json'
        })
            .then(function (res) {
                state.rates = res.slice(0);
                return res;
            })
            .catch(function () { console.log('failed to fetch rates') });
    }

    function renderRates(rates) {
        $ratesList.html(rates.filter(function (item) { return item.appear }).map(renderRow));
    }

    function renderRow(item) {
        return $('<tr />', {
            'class': 'rates__item'
        })
            .append(renderCell(item.name))
            .append(renderCell(item.nominal))
            .append(renderCell(item.value))
            .append(renderCell(renderRemoveBtn(item.id)));
    }

    function renderCell(value) {
        return $('<td />', {
            html: value
        });
    }

    function renderRemoveBtn(id) {
        return $('<button />', {
            'class': 'fa fa-times rates__remove-btn',
            'data-id': id
        });
    }

    function renderSelectList(items) {
        var list = $('<ul />');

        items
            .filter(function (item) { return !item.appear })
            .forEach(function (item) {
               list.append($('<li />', { text: item.name, id: item.id }));
            });

        return list;
    }

    function showModal(html) {
        $modalContent.append(html)
        $modal.addClass('modal_open');
    }

    function closeModal() {
        $modal.removeClass('modal_open');
        $modalContent.empty();
    }
});