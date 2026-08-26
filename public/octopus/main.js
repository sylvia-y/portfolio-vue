// canvas 세팅
let canvas;
let ctx;
canvas = document.createElement("canvas");
ctx = canvas.getContext("2d");
// canvas 크기 설정
canvas.width=500;
canvas.height=700;
document.body.appendChild(canvas);

let backgroundImage,octopusImage,inkImage,enemyImage,gameOverImage;
let gameOver=false; //true 이면 게임이 끝남, false이면 게임 계속
let score = 0;

//문어 좌표
let octopusX = canvas.width/2-47;
let octopusY = canvas.height-94;

let inkList = [] //먹물저장하는 리스트
function Ink(){
    this.x = 0;
    this.y = 0;
    this.init = function(){
        this.x = octopusX +20;
        this.y = octopusY;
        this.alive=true //true 면 살아있는 먹물 false면 죽은 먹물
        inkList.push(this);
    };
    this.update = function() {
        this.y -= 7;
    };
    this.checkHit = function(){
        //먹물.y <= 적군.y and
        //먹물.x >= 적군.x and 
        //먹물.x <= 적군.x + 적군의 넓이
        for(let i=0; i< enemyList.length; i++) {
            if(this.y <= enemyList[i].y && 
                this.x >=enemyList[i].x && 
                this.x <=enemyList[i].x + 48){
                //먹물이 죽음 적군이 없어짐, 점수 획득
                score++;
                this.alive=false; //죽은 먹물
                enemyList.splice(i,1);
            }
        }
    };
}

function generateRandomValue(min,max) {
    let randomNum = Math.floor(Math.random()*(max-min+1))+min;
    return randomNum;
}
//적군 만들기
// 위치는 랜덤, 밑으로 내려옴, 1초마다 생김,
// 적군이바닥에 닿으면 게임오버, 적군과 먹물이 만나면 사라짐+ 점수1점 획득
let enemyList=[]
function Enemy() {
    this.x = 0;
    this.y = 0;
    this.init = function() {
        this.y = 0
        this.x = generateRandomValue(0,canvas.width - 48);
        enemyList.push(this);
    };
    //적군 속도 조절
        this.update = function() {
            this.y += 2;

        if(this.y >= canvas.height - 48){
            gameOver = true;
            console.log("gg")
        }
    }
}

function loadImage(){
    backgroundImage = new Image();
    backgroundImage.src = "images/ocean2.jpg";

    octopusImage =new Image();
    octopusImage.src = "images/octopus.png";

    inkImage =new Image();
    inkImage.src = "images/ink.png";

    enemyImage =new Image();
    enemyImage.src = "images/shark.png";
    
    gameOverImage =new Image();
    gameOverImage.src = "images/over.png";
}

let keysDown={}
function setupKeyboardListener(){
    document.addEventListener("keydown",function(event){


        keysDown[event.keyCode] = true;
    });
    document.addEventListener("keyup",function(event){
        delete keysDown[event.keyCode];

        if(event.keyCode == 32){
            createInk() //먹물생성
        }
    });
}

function createInk(){
    let k = new Ink; // 먹물 하나 생성
    k.init();
    console.log("새로운 먹물 리스트",Ink);
}

function createEnemy(){
    const interval = setInterval(function(){
        let e = new Enemy()
        e.init();
    }, 1000);
}

//오른쪽으로 이동 :x좌표 증가, 왼쪽으로 이동 : x좌표 감소
function update(){
    //right
    if(39 in keysDown){
        octopusX += 5; //문어 속도
    }
    //left
    if(37 in keysDown){
        octopusX -= 5;
    }
    if(octopusX <= 0){
        octopusX = 0;
    }
    if(octopusX >= canvas.width-94){
        octopusX = canvas.width-94;
    }
    //문어의 좌표값이 무한대로 업데이트가 아닌 바닷속에만 있게 하려면?
    //먹물의 y좌표 업데이트
    for(let i=0; i<inkList.length; i++){
        if(inkList[i].alive){
            inkList[i].update();
            inkList[i].checkHit();
        }
    }
    for(let i =0; i<enemyList.length; i++){
        enemyList[i].update();
    }
}

function render(){
    ctx.drawImage(backgroundImage, 0, 0,canvas.width,canvas.height);
    ctx.drawImage(octopusImage,octopusX,octopusY);
    ctx.fillText(`score:${score}`, 20, 20);
    ctx.fillStyle = "white";
    ctx.font = "24px Arial";
    
    for(let i=0; i<inkList.length;i++){
        if(inkList[i].alive){
            ctx.drawImage(inkImage,inkList[i].x,inkList[i].y);
        }
    }
    for(let i=0; i<enemyList.length;i++){
        ctx.drawImage(enemyImage, enemyList[i].x, enemyList[i].y);
    }
}
function main(){
    if(!gameOver){
        update(); //좌표값을 업데이트하고
        render(); //그려주고
        requestAnimationFrame(main);
    }else{
        ctx.drawImage(gameOverImage,50,100,400,400);
    }
    
}

//먹물 만들기
//1.스페이스바를 누르면 먹물 발사
//2.발사 = 먹물 y값은? --/ x값은? 스페이스를 누른 순간의 문어 x좌표
//3.발사된 먹물들은 먹물 배열에 저장을 한다.
//4.모든 먹물들은 x,y좌표값이 있어야 한다.
//5.총알 배열을 가지고 render 그려준다.

loadImage();
setupKeyboardListener();
createEnemy();
main();

