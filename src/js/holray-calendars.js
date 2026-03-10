if (document.querySelector(".holray-calendar")) {
    document.querySelectorAll(".holray-calendar").forEach(el => {
        const $cal = el;
        const $loaderEl = generateLoaderEl($cal);
        const id = $cal.getAttribute("id");
        const url = $cal.getAttribute("data-holray-cal-url");
        const unit = $cal.getAttribute("data-holray-unit");
        const holrayUrl = $cal.getAttribute("data-holray-url");

        const baseUrl = new URL(url);
        baseUrl.searchParams.append("action", "holrayunits_unit_calendar");
        baseUrl.searchParams.append("unit", unit);
        baseUrl.searchParams.append("target", id);

        fetch(baseUrl).then(data => data.text()).then(data => {
            $cal.innerHTML = data;
        });

        function generateLoaderEl(cal) {
            const $loaderEl = document.createElement("div");
            $loaderEl.innerHTML = cal.innerHTML;
            return $loaderEl.children[0];
        }

        function redrawCal(target, unitClass, startDate, duration, berths, pets, selectedDate) {
            baseUrl.searchParams.set("unit", unitClass);
            baseUrl.searchParams.set("target", id);
            baseUrl.searchParams.set("startDate", startDate);
            baseUrl.searchParams.set("duration", duration);
            baseUrl.searchParams.set("berths", berths);
            baseUrl.searchParams.set("numpets", pets);
            baseUrl.searchParams.set("selDate", selectedDate);

            $cal.append($loaderEl);

            fetch(baseUrl).then(data => data.text()).then(data => {
                document.getElementById(target).innerHTML = data;
            })
        }
        function showBlock(fromDate, duration, unitClass, target, pos) {
            for (i = pos; i < pos + duration; i++) {
                element = document.getElementById(target + '_' + i);
                if (element) {
                    element.style.cursor = 'pointer';
                    element.classList.add("hover");
                }
            }
        }
        function hideBlock(target, pos, duration) {
            for (i = pos; i < pos + duration; i++) {
                element = document.getElementById(target + '_' + i);
                if (element) {
                    element.style.cursor = 'pointer';
                    element.classList.remove("hover");
                }
            }
        }
        function doConfirm(holKey) {
            const baseUrl = new URL(holrayUrl);
            baseUrl.searchParams.append("holkey", holKey);
            window.location = baseUrl.toString();
        }

        window.redrawCal = redrawCal;
        window.showBlock = showBlock;
        window.hideBlock = hideBlock;
        window.doConfirm = doConfirm;

    })
}