class slides{
    constructor(){
        this.slide=[];
        this.slide[0]='/imgs/welcome.jpeg';
        this.slide[1]='/imgs/stores.jpeg';
        this.slide[2]='/imgs/E-store.jpeg';

        
        
        this.c=0;
        document.getElementById('b1').addEventListener('click',()=>{
            document.getElementById('image').src= '/imgs/welcome.jpeg';
            this.c=0

        });

        document.getElementById('b2').addEventListener('click',()=>{
            document.getElementById('image').src= '/imgs/stores.jpeg';
            this.c=1

        });

        document.getElementById('b3').addEventListener('click',()=>{
            document.getElementById('image').src='/imgs/E-store.jpeg';
            this.c=2

        });

        // this.change()
        setInterval(()=>{
            this.change();
        },7000);

      
    }
    change(){
        if(this.c<this.slide.length-1){
            this.c++

        }else{
            this.c=0
        }
        document.getElementById('image').src= this.slide[this.c];
    }

}
onload=new slides();