import BLOCKS from "./blocks.js"
$(document).ready(function(){
//DOM
const playground = document.querySelector(".playground > ul");
const gameText = document.querySelector(".game-text");
const scoreDisplay = document.querySelector(".score");
const restartBtn = document.querySelector(".game-text > button");
// Setting
const GAME_ROWS = 20;
const GAME_COLS = 10;
//variables
let score = 0;
let duration = 500;
let downInterval;
let tempMovingItem;

const movingItem = {
    type:"",
    direction:3,
    top:0,
    left:0,
};

init();
//functions
function init(){
    tempMovingItem = { ...movingItem };
    for(let i=0; i < GAME_ROWS; i++) {
        prependNewLine();
    }
    generateNewBlock();
};

function prependNewLine(){
    const li = document.createElement("li");
    const ul = document.createElement("ul");
    for(let j = 0; j < GAME_COLS; j++){
        const matrix =document.createElement("li")
        ul.prepend(matrix)
    }
    li.prepend(ul)
    playground.prepend(li)
};

function renderBlocks(moveType = ""){
    const { type, direction, top, left } = tempMovingItem;
    const movingBlocks = document.querySelectorAll(".moving");
    movingBlocks.forEach(moving => {
        moving.classList.remove(type,"moving");
        // console.log(moving);
    })
    BLOCKS[type][direction].some(block=>{ //forEach는 중간에 멈출 수 없음.
        const x = block[0] + left;
        const y = block[1] + top;
        // console.log(playground.childNodes[y]);

        //삼항 연산자 - 조건 ? 참일 경우 : 거짓일 경우
        const target = playground.childNodes[y] ? playground.childNodes[y].childNodes[0].childNodes[x] : null;
        const isAvailable = checkEmpty(target);
        if (isAvailable) {
            target.classList.add(type,"moving");
        }else {
            tempMovingItem = { ... movingItem };
            if(moveType === 'retry'){
                clearInterval(downInterval);
                showGameoverText();
            }
            setTimeout(()=>{ //이벤트들이 다 실행된 후에 작동 , 콜스택에러 방지
                renderBlocks('retry'); //재귀함수
                if(moveType === "top"){
                    seizeBlock();
                }
            }, 0)
            return true;
        }
    })
    movingItem.left = left ;
    movingItem.top = top ;
    movingItem.direction = direction ;
};

//블럭이 제일 아래칸에 닿았을 경우 - moving 중지
function seizeBlock(){
    const movingBlocks = document.querySelectorAll(".moving");
    movingBlocks.forEach(moving => {
        moving.classList.remove("moving");
        moving.classList.add("seized");
    })
    checkMatch();
};
function checkMatch(){
    const childNodes = playground.childNodes;
    childNodes.forEach(child => {
        let matched = true;
        child.children[0].childNodes.forEach(li => {
            if(!li.classList.contains("seized")){
                matched = false;
            }
        })
        if(matched){
            child.remove();
            prependNewLine();
            score++;
            scoreDisplay.innerText = score;
        }
    })
    generateNewBlock()
}
//새로운 블럭이 내려오게하는 함수
function  generateNewBlock() {
    clearInterval(downInterval);
    downInterval = setInterval(()=>{
        moveBlock('top',1)
    },duration);

    const blockArray = Object.entries(BLOCKS);
    const randomIndex = Math.floor(Math.random() * blockArray.length);
    movingItem.type = blockArray[randomIndex][0]
    movingItem.top = 0;
    movingItem.left = 3;
    movingItem.direction = 0;
    tempMovingItem = { ... movingItem};
    renderBlocks()
};

function checkEmpty(target){
    if(!target || target.classList.contains("seized")){
        return false;
    }
    return true;
};

function  moveBlock(moveType, amount){
    tempMovingItem[moveType] += amount;
    renderBlocks(moveType);
}
function changeDirection(){
    const direction = tempMovingItem.direction;
    direction ===3 ? tempMovingItem.direction = 0 : tempMovingItem.direction += 1;
    renderBlocks();
}
function dropBlock(){
    clearInterval(downInterval);
    downInterval =setInterval(()=>{
        moveBlock("top", 1);
    },10)
}
function showGameoverText() {
    gameText.style.display = "flex"
}
//event handling
document.addEventListener("keydown", e => {
    switch(e.keyCode){
        case 39:
            moveBlock("left", 1);
            break;
        case 37:
            moveBlock("left", -1);
            break;
        case 40:
            moveBlock("top", 1);
            break;
        //direction
        case 38:
            changeDirection();
            break;
        case 32:
            dropBlock();
        default:
            break;
    }
    // console.log(e)
})
restartBtn.addEventListener("click", ()=> {
    playground.innerHTML = "";
    gameText.style.display = "none";
    init();
});

});