var app = new Vue({
    el: '#app',
    data: {
        currentSlide: 0,
        isPreviousSlide: false,
        isFirstLoad: true,
        slides: [
            {
                headlineFirstLine: "У вас авто",
                headlineSecondLine: "У нас детали",
                sublineFirstLine: "ИНТЕРНЕТ-МАГАЗИН",
                sublineSecondLine: "OPORA.DN.UA",
                bgImg: "images/vue/car-skyline_p.png",
                rectImg: "images/vue/purple.png"
            },
            {
                headlineFirstLine: "Качество",
                headlineSecondLine: "Гарантия",
                sublineFirstLine: "ДОСТАВКА",
                sublineSecondLine: "ОПЛАТА",
                bgImg: "images/vue/salon.png",
                rectImg: "images/vue/purple.png"
            },
            {
                headlineFirstLine: "Консультация",
                headlineSecondLine: "Страхование",
                sublineFirstLine: "ДОСКА ОБЪЯВЛЕНИЙ",
                sublineSecondLine: "ПОИСК",
                bgImg: "images/vue/carmanufactures.png",
                rectImg: "images/vue/purple.png"
            }
        ]
    },
  mounted: function () {
    var productRotatorSlide = document.getElementById("app");
        var startX = 0;
        var endX = 0;

        productRotatorSlide.addEventListener("touchstart", (event) => startX = event.touches[0].pageX);

        productRotatorSlide.addEventListener("touchmove", (event) => endX = event.touches[0].pageX);

        productRotatorSlide.addEventListener("touchend", function(event) {
            var threshold = startX - endX;

            if (threshold < 150 && 0 < this.currentSlide) {
                this.currentSlide--;
            }
            if (threshold > -150 && this.currentSlide < this.slides.length - 1) {
                this.currentSlide++;
            }
        }.bind(this));
  },
    methods: {
        updateSlide(index) {
            index < this.currentSlide ? this.isPreviousSlide = true : this.isPreviousSlide = false;
            this.currentSlide = index;
            this.isFirstLoad = false;
        }
    }
})
