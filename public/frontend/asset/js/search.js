  document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const resultsWrap = document.getElementById('searchResultsWrap');
            const resultsList = document.getElementById('searchResultsList');
            const noResultMsg = document.getElementById('searchNoResult');
            const defaultWrap = document.getElementById('defaultProductsWrap');

            let debounceTimer;

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    resultsWrap.style.display = 'none';
                    defaultWrap.style.display = 'block';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('frontend.search') }}?query=${encodeURIComponent(query)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            defaultWrap.style.display = 'none';
                            resultsWrap.style.display = 'block';
                            resultsList.innerHTML = '';

                            if (data.results.length === 0) {
                                noResultMsg.style.display = 'block';
                                return;
                            }

                            noResultMsg.style.display = 'none';

                            data.results.forEach(item => {
                                resultsList.insertAdjacentHTML('beforeend', `
                        <a href="${item.url}" class="col-6 col-md-3 thumb-card" style="text-decoration:none; color:inherit;">
                            <img src="${item.image}" alt="${item.name}">
                            <div class="p-name">${item.name}</div>
                            <div class="p-price">Tk ${Number(item.price).toLocaleString()}</div>
                        </a>
                    `);
                            });
                        })
                        .catch(err => console.error('Search error:', err));
                }, 350); // debounce delay
            });
        });