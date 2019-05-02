import axios from "axios";

let baseUrl = window.App.apiRoute;
let csrfToken = window.App.csrfToken;

const http = axios.create({
  baseURL: baseUrl,
  headers: {
    common: {
      "X-CSRF-TOKEN": csrfToken,
      "X-Requested-With": "XMLHttpRequest"
    }
  }
});

export default http;
