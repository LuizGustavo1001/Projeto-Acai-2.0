const body = document.body

const smileFace = `
    <svg viewBox='0 0 40 40' fill='none' xmlns='http://www.w3.org/2000/svg'>
        <path d='M15 26.6667C16.4173 27.7172 18.141 28.3334 20 28.3334C21.859 28.3334 23.5827 27.7172 25 26.6667' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
        <path d='M25.0007 20C25.9211 20 26.6673 18.8807 26.6673 17.5C26.6673 16.1193 25.9211 15 25.0007 15C24.0802 15 23.334 16.1193 23.334 17.5C23.334 18.8807 24.0802 20 25.0007 20Z' fill='currentColor'/>
        <path d='M15.0007 20C15.9211 20 16.6673 18.8807 16.6673 17.5C16.6673 16.1193 15.9211 15 15.0007 15C14.0802 15 13.334 16.1193 13.334 17.5C13.334 18.8807 14.0802 20 15.0007 20Z' fill='currentColor'/>
        <path d='M36.6673 19.9999C36.6673 27.8566 36.6673 31.7851 34.2265 34.2258C31.7858 36.6666 27.8573 36.6666 20.0007 36.6666C12.1439 36.6666 8.21553 36.6666 5.77477 34.2258C3.33398 31.7851 3.33398 27.8566 3.33398 19.9999C3.33398 12.1432 3.33398 8.2148 5.77477 5.77404C8.21553 3.33325 12.1439 3.33325 20.0007 3.33325C27.8573 3.33325 31.7858 3.33325 34.2265 5.77404C35.8495 7.39694 36.3933 9.6775 36.5755 13.3333' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
    </svg>
`;
const sadFace = `
    <svg viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
        <path d='M9 17C9.85038 16.3697 10.8846 16 12 16C13.1154 16 14.1496 16.3697 15 17' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'/>
            <ellipse cx='15' cy='10.5' rx='1' ry='1.5' fill='currentColor'/>
            <ellipse cx='9' cy='10.5' rx='1' ry='1.5' fill='currentColor'/>
        <path d='M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C21.5093 4.43821 21.8356 5.80655 21.9449 8' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'/>
    </svg>
`;


// toggle theme + last localStorage theme 
const toggleThemeBox = document.querySelectorAll(".toggle-theme")
if(toggleThemeBox){
    toggleThemeBox.forEach(box => {
        box.addEventListener("click", toggleTheme)
    })

}

function toggleTheme(){
    const selectedBg = document.querySelectorAll(".toggle-theme .selected-bg")
    selectedBg.forEach(item => {
        item.classList.toggle("outer")
    })

    const iconBg = document.querySelectorAll(".toggle-theme .icon-bg")
    iconBg.forEach(icon => {
        icon.classList.toggle("active")
    })

    body.classList.toggle("dark-mode")

    const isDark = body.classList.contains("dark-mode")
    localStorage.setItem("theme", isDark ? "dark" : "light")

}

const lastTheme = localStorage.getItem("theme")
if(lastTheme == "dark"){
    toggleTheme()
}

// Mobile sidebar toggle
const toggleSidebarButton = document.querySelectorAll(".icon-button.menu")
const dazzlesBg = document.querySelector(".dazzles-bg")
const cardNav = document.querySelector("header nav")

document.addEventListener("click", (e) => {
    const clickedInsideNav = e.target.closest("header nav")
    const clickedToggle = e.target.closest(".icon-button.menu")

    if(! clickedInsideNav && !clickedToggle){
        if(cardNav.classList.contains("open")){
            toggleSidebar()
        }
    }
})

toggleSidebarButton.forEach(button => {
    button.addEventListener("click", toggleSidebar)
})

function toggleSidebar(){
    dazzlesBg.classList.toggle("open")
    cardNav.classList.toggle("open")
}



/* toggle warning display */
const warningBox = document.querySelector(".warning")
if (warningBox) {
    const removeBox = () => {
        warningBox.classList.add("fade-out")
        setTimeout(() => warningBox.remove(), 500)
    }

    warningBox.addEventListener("click", removeBox)

    setTimeout(removeBox, 10000)
}

const cookie_message = getCookie("warning_message")
const cookie_type = getCookie("warning_type")
if(cookie_message && cookie_type){
    fillWarning(cookie_message, Number(cookie_type))
}

function getCookie(name){
    const cookies = document.cookie.split("; ")
    for(let cookie of cookies){
        const [key, value] = cookie.split("=")
        if(key === name){
            return decodeURIComponent(value)
        }
    }
    return null
}

function fillWarning(message, type){
    let icon            = sadFace
    let warningClass    = "error"

    if(type == 1){ // success
        icon = smileFace
        warningClass = "success"
    }

    const warningMsgs = {
            revAdd            : "Reversão de adição <strong>realizada com sucesso</strong>.",
            revMod            : "Reversão de modificação <strong>realizada com sucesso</strong>.",
            revRem            : "Reversão de remoção <strong>realizada com sucesso</strong>.",
            orderConfirmed    : "<strong>Pedido enviado</strong> para nossa central.",
            loginSuccess      : "Agora voce pode navegar pelo site e fazer compras em seu nome.",
            notAdmin          : "É preciso fazer <strong>login como administrador</strong> para acessar a página de gerenciamento.",
            prodAdd           : "<strong>Produto adicionado</strong> com sucesso ao <strong>carrinho</strong>.",
            adminNotAllowed   : "É preciso fazer <strong>login como cliente</strong> para acessar A página anterior.",
            makeClient        : "<strong>Novo cliente adicionado</strong> com sucesso.",
            removeS           : "Sucesso ao <strong>remover um item</strong> no banco de dados.",
            addProduct        : "Sucesso ao <strong>adicinar produto</strong> no banco de dados.",
            addVersion        : "Sucesso ao <strong>adicinar versão de um produto</strong> ao banco de dados.",
            makeAdmin         : "<strong>Novo administrador Adicionado</strong> com sucesso.",
            noItem            : "É preciso adicionar algum produto ao carrinho para concluir a compra.",
            outOfOrder        : "<strong>Versão do Produto</strong> selecionado está <strong>indisponível</strong>.",
            errorLogin        : "Email ou senha <strong>incorretos</strong>, tente novamente ou <strong>cadastre-se</strong> no link abaixo.",
            timeout           : "<strong>Sessão expirada</strong>, realize seus login novamente.",
            unkUser           : "<strong>Realize seu login</strong> para <strong>adicionar produtos</strong> ao carrinho.",
            registered        : "Credencias <strong>cadastradas com sucesso</strong>, <strong>realize seu Login</strong>.",
            newEmail          : "Email <strong>alterado com sucesso</strong>. Autentique-se novamente para continuar.",
            newPassword       : "Senha <strong>alterada com sucesso</strong>. Autentique-se novamente para continuar.",
            logout            : "Deslogado com sucesso. ",
            emailExists       : "Email inserido já está <strong>cadastrado</strong> no site.",
            invalidDomain     : "<strong>Domínio</strong> do email digitado <strong>inválido</strong>.",
            sameMail          : "<strong>Email Anterior</strong> e <strong>Novo Email</strong> inseridos são os mesmos. ",
            wrongMail         : "<strong>Email Inserido</strong> não está cadastrado. Tente Novamente.",
            wrongToken        : "<strong>Token inserido</strong> incorreto. Tente Novamente.",
            successText       : "<strong>Sucesso</strong> ao alterar dado",
            errorText         : "O <strong>valor inserido</strong> é o mesmo já cadastrado.",
            wrongP            : "<strong>Senha Anterior Inserida</strong> não está cadastrada.",
            sameP             : "<strong>Senha Anterior</strong> e <strong>Nova Senha</strong> inseridas são as mesmas.",
            prodRem           : "<strong>Produto</strong> removido do carrinho com <strong>sucesso</strong>.",
            dev               : "Função ainda em <strong>desenvolvimento</strong>",
    }

    if(warningMsgs[message]){
        const warningBox = document.createElement("div");
        warningBox.classList.add("warning", "fade-in", warningClass);

        warningBox.innerHTML = `
            ${icon}
            <span>${warningMsgs[message]} <em>Clique nesta mensagem para fechá-la</em>.</span>
        `
        body.insertAdjacentElement("afterbegin", warningBox)

        // remove message when click or after 10 seconds
        const removeBox = () => {
            warningBox.classList.add("fade-out")
            setTimeout(() => warningBox.remove(), 500)
        }

        warningBox.addEventListener("click", removeBox)

        setTimeout(removeBox, 10000)

    // clear warning cookies
    document.cookie = "warning_message=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "warning_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }
}