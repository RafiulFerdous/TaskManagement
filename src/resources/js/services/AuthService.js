import Environment from "@/environments/environment";
import axios from "axios";
class AuthService {
  apiUrl = Environment.apiUrl;

  setToken(token, expiry) {
    // console.log('token',token);
    let d = new Date(expiry);
    let expires = "expires=" + d.toUTCString();
    document.cookie =
      "auth_access=" + token + ";" + expires + ";path=/";
  }


  getToken() {
    var cookies = document.cookie.split(";");
    var access_token = cookies.find((cookie) =>
      cookie.includes("auth_access=")
    );

    if (!access_token) {
      return { token: null };
    }
    return { token: access_token.split("=")[1] };
  }

  deleteToken() {
    document.cookie =
      "auth_access=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie =
      "auth_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  }
}
export default new AuthService();
