import Token from "./Token";
import AppStorage from "./AppStorage";

class User {

    responseAfterLogin(res) {
        const access_token = res.data.access_token;
        const username = res.data.name;
        const isAdmin = res.data.is_admin;
        if (Token.isValid(access_token)) {
            AppStorage.store(access_token, username, isAdmin);
        }
    }

    hasToken(){
        const storeToken = localStorage.getItem('token');
        if (storeToken) {
            return !!Token.isValid(storeToken);
        }

        return false;
    }

    loggedIn() {
        return this.hasToken();
    }

    name() {
        if (this.loggedIn()){
            return localStorage.getItem('user');
        }
    }

    id() {
        if (this.loggedIn()){
            const payload = Token.payload(localStorage.getItem('token'));
            return payload.sub
        }
        return false;
    }

    isAdmin() {
        const storeToken = localStorage.getItem('isUserAdmin');
        if(storeToken === '0') {
            return false;
        } else {
            return true;
        }
    }

}

export default User = new User();
