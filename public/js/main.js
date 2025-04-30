/**
 * Kapcsolat űrlap kliens oldali ellenőrzése.
 * @returns {boolean} True, ha az űrlap érvényes, különben false.
 */
function ellenorizKapcsolat() {
    let valid = true; // Feltételezzük, hogy minden rendben van

    // Hibaüzenetek és stílusok alaphelyzetbe állítása
    const hibaSpanok = document.querySelectorAll('.hiba-uzenet');
    hibaSpanok.forEach(span => span.textContent = ''); // Hibaüzenetek törlése
    const inputok = document.querySelectorAll('form[name="kapcsolaturlap"] input, form[name="kapcsolaturlap"] textarea');
    inputok.forEach(input => input.classList.remove('valid', 'invalid')); // Stílusok eltávolítása

    // Név ellenőrzése
    const nevInput = document.getElementById('nev');
    const nevHiba = document.getElementById('nev-hiba');
    if (!nevInput || nevInput.value.trim().length < 5) {
        valid = false;
        if (nevInput) nevInput.classList.add('invalid');
        if (nevHiba) nevHiba.textContent = 'A név megadása kötelező (minimum 5 karakter).';
    } else {
         if (nevInput) nevInput.classList.add('valid');
    }

    // E-mail ellenőrzése (egyszerű reguláris kifejezéssel)
    const emailInput = document.getElementById('email');
    const emailHiba = document.getElementById('email-hiba');
    // Egyszerűbb regex, de lefedi a legtöbb esetet
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailInput || !emailPattern.test(emailInput.value.trim())) {
        valid = false;
        if (emailInput) emailInput.classList.add('invalid');
        if (emailHiba) emailHiba.textContent = 'Érvénytelen e-mail formátum.';
    } else {
         if (emailInput) emailInput.classList.add('valid');
    }

    // Üzenet ellenőrzése
    const uzenetInput = document.getElementById('uzenet');
    const uzenetHiba = document.getElementById('uzenet-hiba');
    if (!uzenetInput || uzenetInput.value.trim() === '') {
        valid = false;
        if (uzenetInput) uzenetInput.classList.add('invalid');
        if (uzenetHiba) uzenetHiba.textContent = 'Az üzenet megadása kötelező.';
    } else {
         if (uzenetInput) uzenetInput.classList.add('valid');
    }

    // Ha az űrlap érvénytelen, megakadályozzuk a küldést
    return valid;
}

// Megjegyzés: A feladatleírás szerint a Küld gombot nem kell letiltani/engedélyezni
// a validáció alapján ennél az űrlapnál, csak a validációt kell elvégezni
// az onsubmit eseményben.