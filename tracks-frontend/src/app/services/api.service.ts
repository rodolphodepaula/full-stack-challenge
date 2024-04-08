import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { AuthService } from './auth.service';
import 'rxjs/add/operator/map';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private apiURL = 'http://localhost/api/';

  constructor(private http: HttpClient, private authService: AuthService) { }

  ApiPost(dados: any, api: string, isPost: boolean) {
    const token = this.authService.getToken();
    const httpOptions = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      })
    };
    const url = this.apiURL + api;
    if (isPost) {
      return this.http.post(url, JSON.stringify(dados), httpOptions)
      .map(res => res);
    }

    return this.http.put(url, JSON.stringify(dados), httpOptions)
      .map(res => res);
  }

  ApiGet(api: string, options: object) {
    const token = this.authService.getToken();
    const httpOptions = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      }),
      ...options
    };
    const url = this.apiURL + api;
    return this.http.get(url, httpOptions)
      .map(res => res);
  }

  ApiDelete(uuid: string, api: string) {
    const token = this.authService.getToken();
    const httpOptions = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      })
    };
    const url = `${this.apiURL}${api}/${uuid}`;
    return this.http.delete(url, httpOptions)
      .map(res => res);
  }
}
