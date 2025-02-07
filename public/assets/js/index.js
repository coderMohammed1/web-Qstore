class Slides {
    constructor() {
        this.slide = [
            '/imgs/welcome.jpeg',
            '/imgs/stores.jpeg',
            '/imgs/E-store.jpeg'
        ];

        this.images = {}; // Cache for preloaded images (by this we willl only request iamges once the page load)
        this.c = 0;

        // Preload images
        this.slide.forEach(src => {
            const img = new Image();
            img.src = src;
            this.images[src] = img;
        });

       
        document.getElementById('b1').addEventListener('click', () => this.setImage(0));
        document.getElementById('b2').addEventListener('click', () => this.setImage(1));
        document.getElementById('b3').addEventListener('click', () => this.setImage(2));

        
        setInterval(() => this.change(), 7000); // change img per 7 seconeds
    }

    setImage(index) {
        this.c = index;
        document.getElementById('image').src = this.images[this.slide[index]].src;
    }

    change() {
        this.c = (this.c + 1) % this.slide.length;
        document.getElementById('image').src = this.images[this.slide[this.c]].src;
    }
}

// Initialize on page load
window.onload = () => new Slides();