import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['category', 'subcategory'];

    static values = {
        url: String,
        route: String
    };


    connect() {
        // console.log('Subcategory controller connected');
        console.log(`Using route: ${this.routeValue}`);
    }

    load() {
        // console.log('Loading subcategories...');
        const categoryId = this.categoryTarget.value;

        this.subcategoryTarget.innerHTML = '<option value="">-- Select subcategory --</option>';
        this.subcategoryTarget.disabled = true;

        if (!categoryId) {
            return;
        }

        // fetch(`/api/category/${categoryId}`, {
        fetch(this.urlValue.replace('PLACEHOLDER', categoryId), {
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {

                // this.subcategoryTarget.innerHTML = '';
                this.subcategoryTarget.options.length = 0;

                Object.entries(data).forEach(([key, label]) => {
                    const option = document.createElement('option');
                    option.value = key;      // enum name, e.g. CAREERS
                    option.textContent = label; // human label
                    this.subcategoryTarget.appendChild(option);
                });

                this.subcategoryTarget.disabled = false;
            })
            .catch(error => {
                console.error('Error loading subcategories:', error);
            });
    }
}
