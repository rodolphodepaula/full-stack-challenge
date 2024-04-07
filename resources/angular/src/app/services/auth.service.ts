import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { Router } from '@angular/router';
import { ToastService } from './toast.service';
import { tap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})

export class AuthService {
  private currentUserSubject: BehaviorSubject<any>;
  public currentUser: Observable<any>;

  constructor(
    private http: HttpClient,
    private router: Router,
    private toastService: ToastService
  ) {
    this.currentUserSubject = new BehaviorSubject<any>(JSON.parse(localStorage.getItem('currentUser')));
    this.currentUser = this.currentUserSubject.asObservable();
  }

  public get currentUserValue() {
    return this.currentUserSubject.value;
  }

  getToken(): string | null {
    const currentUser = localStorage.getItem('currentUser');
    if (currentUser) {
      const user = JSON.parse(currentUser);
      return user.token;
    }
    return null;
  }

  login(email: string, password: string) {
    return this.http.post<any>('/api/login', { email, password })
      .pipe(map(user => {
        localStorage.setItem('currentUser', JSON.stringify(user));
        this.currentUserSubject.next(user);
        return user;
      }));
  }

  logout() {
    //remover o user do localstorage.
    localStorage.removeItem('currentUser');
    this.toastService.showToast('Você saiu do sistema ONERpm.', 3000);
    this.currentUserSubject.next(null);
    return this.http.post<any>('/api/logout', {}).pipe(
      tap(() => {
        localStorage.removeItem('currentUser');
        this.currentUserSubject.next(null);
      })
    );
  }

  public isLoggedIn(): Observable<boolean> {
    return this.currentUserSubject.asObservable().pipe(map(user => {
      return user && user.token != null;
    }));
  }
}
