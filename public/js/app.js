$(document).ready(function() {
    $('#searchButton').on('click', function() {
        const cityQuery = $('#cityInput').val().trim();
        const messageContainer = $('#message');
        const resultsContainer = $('#searchResults');

        if (cityQuery) {
            $.ajax({
                url: '/index.php?action=searchCity',
                method: 'POST',
                data: { query: cityQuery },
                success: function(response) {
                    resultsContainer.empty();
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
                },
                error: function(xhr, status, error) {
                    messageContainer.text('No results found').removeClass('hidden');
                    console.error('Error due to city search: ', status, error);
                }
            });
        } else {
            messageContainer.text('Please, enter city for search').removeClass('hidden');
        }
    });

    function selectCity(cityName) {
        $.ajax({
            url: '/index.php?action=setCity', 
            method: 'POST',
            data: { city: cityName },
            success: function() {
                window.location.href = '/index.php';
            },
            error: function(xhr, status, error) {
                console.error('Error due to city selection: ', status, error);
            }
        });
    }
});
