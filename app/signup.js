const button = document.querySelector('.btn'); 
const mdp = document.querySelector('#password');
const mdpRepeat = document.querySelector('#password_repeat');

button.addEventListener('click', () =>{
    if(mdp.value !== mdpRepeat.value){
        mdp.value = ""; 
        mdpRepeat.value
        mdp.placeholder = "Les mots de passes sont différents";
    }
})

mdp.addEventListener('keyup', ()=>{
    mdp.placeholder = "";
})