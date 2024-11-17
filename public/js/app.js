$(document).ready(function() {
    const modalContent = $('.modal-content');
    const messageTemplate = $('<div id="message"></div>'); 

    $('#searchButton').on('click', function() {
        const cityQuery = $('#cityInput').val().trim();
        const resultsContainer = $('#searchResults');

        modalContent.find('#message').remove();
        resultsContainer.empty();

        if (cityQuery === '') {
            const message = messageTemplate.clone().text('Please, enter a city to search');
            modalContent.append(message);
            return;
        }

        $.ajax({
            url: '/index.php?action=searchCity',
            method: 'POST',
            data: { query: cityQuery },
            success: function(response) {
                resultsContainer.empty();

                if (Array.isArray(response) && response.length === 0) {
                    const message = messageTemplate.clone().text('No results found');
                    modalContent.append(message);
                } else if (Array.isArray(response)) {
                    response.forEach(city => {
                        const cityItem = $(`
                            <div class="search-result" data-city="${city.name}">
                                <p class="city-name">${city.name}</p>
                                <p class="country">${city.country}, ${city.region}</p>
                            </div>
                        `);
                        cityItem.on('click', function() {
                            selectCity(city.name);
                        });
                        resultsContainer.append(cityItem);
                    });
                } else {
                    const message = messageTemplate.clone().text('Unexpected response format, please try again later');
                    modalContent.append(message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error during city search:', status, error);
                const message = messageTemplate.clone().text('Error during city search, please try again later');
                modalContent.append(message);
            }
        });
    });

    function selectCity(cityName) {
        $.ajax({
            url: '/index.php?action=setCity', 
            method: 'POST',
            data: { city: cityName },
            success: function() {
                window.location.href = '/index.php?action=index';
            },
            error: function(xhr, status, error) {
                console.error('Error during city selection:', status, error);
            }
        });
    }
});
