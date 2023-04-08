import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth/services/auth.service';

@Injectable()
export class AuthGuardService {

  constructor(
    private _auth: AuthService,
    private _router: Router,
  ) {}

  canActivate(): boolean {
    if (!this._auth.isAuthenticated()) {
      this._router.navigate(['/']);
      return false;
    }
    return true;
  }
}
