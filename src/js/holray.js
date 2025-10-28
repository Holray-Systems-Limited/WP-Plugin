class MultiSelect {
    constructor(selector) {
        this.select = document.querySelector(selector);
        if (!this.select) return;

        // Hide original select
        this.select.style.display = 'none';

        // Create wrapper
        this.wrapper = document.createElement('div');
        this.wrapper.classList.add('holray-multiselect');

        // Create display box
        this.display = document.createElement('div');
        this.display.classList.add('holray-formcontrol');
        this.display.textContent = 'Select options';

        // Create dropdown
        this.dropdown = document.createElement('div');
        this.dropdown.classList.add('holray-multiselect-dropdown');
        this.dropdown.style.display = 'none';

        // Build checkboxes for each option
        Array.from(this.select.options).forEach(option => {
            const item = document.createElement('label');
            item.classList.add('holray-multiselect-item');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = option.value;
            checkbox.checked = option.selected;

            checkbox.addEventListener('change', () => {
                option.selected = checkbox.checked;
                this.updateDisplay();
            });

            const text = document.createTextNode(option.text);
            item.appendChild(checkbox);
            item.appendChild(text);
            this.dropdown.appendChild(item);
        });

        this.display.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = this.dropdown.style.display === 'block';
            document.querySelectorAll('.multi-select-dropdown').forEach(dd => dd.style.display = 'none');
            this.dropdown.style.display = isOpen ? 'none' : 'block';
            if (isOpen) {
                this.display.classList.remove("is-open");
            } else {
                this.display.classList.add("is-open");
            }
        });

        document.addEventListener('click', () => {
            this.dropdown.style.display = 'none';
            this.display.classList.remove("is-open");
        });

        this.wrapper.appendChild(this.display);
        this.wrapper.appendChild(this.dropdown);
        this.select.parentNode.insertBefore(this.wrapper, this.select.nextSibling);

        this.updateDisplay();
    }

    updateDisplay() {
        const selected = Array.from(this.select.selectedOptions).map(o => o.text);
        this.display.textContent = selected.length ? selected.join(', ') : 'Select options';
    }
}


if (document.querySelector(".holray-searchform .holray-searchform-mobile .holray-btn")) {
    (function () {
        const btn = document.querySelector(".holray-searchform .holray-searchform-mobile .holray-btn");
        const form = document.querySelector(".holray-searchform .holray-searchform-inner");

        btn.addEventListener("click", function (e) {
            form.style.display = "block";
            btn.parentElement.style.display = "none";
            e.preventDefault();
        });
    })();
}

if (document.querySelector(".holray-searchform #holray_features")) {
    (function () {
        new MultiSelect(".holray-searchform #holray_features");
    })();
}