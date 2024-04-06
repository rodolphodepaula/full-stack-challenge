import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router } from '@angular/router';
import { AuthService } from './services/auth.service';

@Injectable({ providedIn: 'root'})
export class AuthGuard implements CanActivate {
  constructor(
    private route: Router,
    private authService: AuthService
  ){}

  canActivate(route: ActivatedRouteSnapshot,state: RouterStateSnapshot) {
    const currentUser = this.authService.currentUserValue;
    if (currentUser){
      return true;
    }

    //Para não logado, redireciona para o login
    this.route.navigate(['/login'], {queryParams: {
      returnUrl: state.url
    }});

    return false;
  }
}
