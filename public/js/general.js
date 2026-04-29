const smileFace = `
    <svg viewBox='0 0 40 40' fill='none' xmlns='http://www.w3.org/2000/svg'>
        <path d='M15 26.6667C16.4173 27.7172 18.141 28.3334 20 28.3334C21.859 28.3334 23.5827 27.7172 25 26.6667' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
        <path d='M25.0007 20C25.9211 20 26.6673 18.8807 26.6673 17.5C26.6673 16.1193 25.9211 15 25.0007 15C24.0802 15 23.334 16.1193 23.334 17.5C23.334 18.8807 24.0802 20 25.0007 20Z' fill='currentColor'/>
        <path d='M15.0007 20C15.9211 20 16.6673 18.8807 16.6673 17.5C16.6673 16.1193 15.9211 15 15.0007 15C14.0802 15 13.334 16.1193 13.334 17.5C13.334 18.8807 14.0802 20 15.0007 20Z' fill='currentColor'/>
        <path d='M36.6673 19.9999C36.6673 27.8566 36.6673 31.7851 34.2265 34.2258C31.7858 36.6666 27.8573 36.6666 20.0007 36.6666C12.1439 36.6666 8.21553 36.6666 5.77477 34.2258C3.33398 31.7851 3.33398 27.8566 3.33398 19.9999C3.33398 12.1432 3.33398 8.2148 5.77477 5.77404C8.21553 3.33325 12.1439 3.33325 20.0007 3.33325C27.8573 3.33325 31.7858 3.33325 34.2265 5.77404C35.8495 7.39694 36.3933 9.6775 36.5755 13.3333' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
    </svg>
`
const sadFace = `
    <svg viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
        <path d='M9 17C9.85038 16.3697 10.8846 16 12 16C13.1154 16 14.1496 16.3697 15 17' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'/>
            <ellipse cx='15' cy='10.5' rx='1' ry='1.5' fill='currentColor'/>
            <ellipse cx='9' cy='10.5' rx='1' ry='1.5' fill='currentColor'/>
        <path d='M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C21.5093 4.43821 21.8356 5.80655 21.9449 8' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'/>
    </svg>
`


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
            revAdd            : "Reversão de adição realizada com sucesso",
            revMod            : "Reversão de modificação realizada com sucesso",
            revRem            : "Reversão de remoção realizada com sucesso",
            orderConfirmed    : "Pedido enviado para nossa central",
            loginSuccess      : "Agora voce pode navegar pelo site e fazer compras em seu nome",
            notAdmin          : "É preciso fazer login como administrador para acessar a página de gerenciamento",
            prodAdd           : "Produto adicionado com sucesso ao carrinho",
            adminNotAllowed   : "É preciso fazer login como cliente para acessar A página anterior",
            makeClient        : "Novo cliente adicionado com sucesso",
            removeS           : "Sucesso ao remover um item no banco de dados",
            addProduct        : "Sucesso ao adicinar produto no banco de dados",
            addVersion        : "Sucesso ao adicinar versão de um produto ao banco de dados",
            makeAdmin         : "Novo administrador Adicionado com sucesso",
            noItem            : "É preciso adicionar algum produto ao carrinho para concluir a compra",
            outOfOrder        : "Versão do Produto selecionado está indisponível",
            errorLogin        : "Email ou senha incorretos, tente novamente ou cadastre-se no link abaixo",
            timeout           : "Sessão expirada, realize seus login novamente",
            unkUser           : "Realize seu login para adicionar produtos ao carrinho",
            registered        : "Credencias cadastradas com sucesso, realize seu Login",
            newEmail          : "Email alterado com sucesso. Autentique-se novamente para continuar",
            newPassword       : "Senha alterada com sucesso. Autentique-se novamente para continuar",
            logout            : "Deslogado com sucesso",
            emailExists       : "Email inserido já está cadastrado no site",
            invalidDomain     : "Domínio do email digitado inválido",
            sameMail          : "Email Anterior e Novo Email inseridos são os mesmos",
            wrongMail         : "Email Inserido não está cadastrado. Tente Novamente",
            wrongToken        : "Token inserido incorreto. Tente Novamente",
            successText       : "Sucesso ao alterar dado",
            errorText         : "O valor inserido é o mesmo já cadastrado",
            wrongP            : "Senha Anterior Inserida não está cadastrada",
            sameP             : "Senha Anterior e Nova Senha inseridas são as mesmas",
            prodRem           : "Produto removido do carrinho com sucesso",
            error             : "Erro interno. Tente novamente ou contate suporte técnico",
            dev               : "Função ainda em desenvolvimento",
    }

    if(warningMsgs[message]){
        const warningBox = document.createElement("div")
        warningBox.classList.add("warning", "fade-in", warningClass)

        warningBox.innerHTML = `
            ${icon}
            <span><strong>${warningMsgs[message]}</strong>. <em>Clique nesta mensagem para fechá-la</em>.</span>
        `
        document.body.insertAdjacentElement("afterbegin", warningBox)

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

    // regular toggle-theme
    let iconBg = document.querySelectorAll(".toggle-theme .icon-bg")

    // outer toggle-theme
    if(iconBg.length == 0) iconBg = document.querySelectorAll(".toggle-theme .toggle-icon")
    
    iconBg.forEach(icon => {icon.classList.toggle("active")})

    document.body.classList.toggle("dark-mode")

    const isDark = document.body.classList.contains("dark-mode")
    localStorage.setItem("theme", isDark ? "dark" : "light")

}

const lastTheme = localStorage.getItem("theme")
if(lastTheme == "dark"){
    toggleTheme()
}


// Mobile sidebar toggle
const toggleSidebarButton = document.querySelectorAll(".icon-btn.menu")
const dazzlesBg = document.querySelector(".dazzles-bg")
const aside = document.querySelector("aside")
const cardNav = document.querySelector(".aside-hero")

if (cardNav) {

    document.addEventListener("click", (e) => {
        const clickedInsideNav = e.target.closest("aside.regular, aside.outer")
        const clickedToggle = e.target.closest(".icon-btn.menu")

        if (!clickedInsideNav && !clickedToggle && aside.classList.contains("open")) {
            toggleSidebar()
        }
    })

    toggleSidebarButton.forEach(button => {
        button.addEventListener("click", toggleSidebar)
    })

    function toggleSidebar(){
        if (dazzlesBg) dazzlesBg.classList.toggle("open")
        aside.classList.toggle("open")
    }
}


// spinning wheel button animation when click
const form = document.querySelector("form")
const regularBntAwait = document.querySelector(".regular-btn.await")

if(regularBntAwait && form){
    form.addEventListener("submit", () => {
        if(! form.checkValidity()) return

        verifyBtn()
    })
}else if(regularBntAwait){ // button without form
    regularBntAwait.addEventListener("click", (event) => {
        event.preventDefault()

        verifyBtn()

        requestAnimationFrame(() => {
            window.location.href = regularBntAwait.href
        })
    })
}

function verifyBtn(){
    const wheel = regularBntAwait.querySelector(".wheel")
    const text  = regularBntAwait.querySelector("span")

    if(! regularBntAwait.disabled){
        regularBntAwait.disabled = true
        wheel.classList.remove("inactive")
        text.style.display = 'none'
    }
}