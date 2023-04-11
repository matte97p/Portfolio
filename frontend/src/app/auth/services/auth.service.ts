import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

import { tap } from 'rxjs/operators';
import { MessagesService } from 'src/app/shared/services/messages.service';

@Injectable({
  providedIn: 'root',
})
export class AuthService {

  private backend_api = 'https://backend-portfolio.test/';
  private auth_api = 'auth/';
  private redirectUrl: string | null = '/home'; // @todo get from role

  constructor(
    private _router: Router,
    private _http: HttpClient,
    private _messagesService: MessagesService
  ) { }

  static getToken() {
    return localStorage.getItem('access_token')
  }

  public isAuthenticated(): boolean {
    return !!localStorage.getItem('access_token');
  }

  public login(formData: any): any {
    return this._http.post<any>(this.backend_api + this.auth_api + 'oauth2', formData)
      .pipe(
        tap(
        {
          next: (data: any) => {
            this._messagesService.success('Login', data.message);

            this.setToken('access_token', data.access_token);
            this.redirect(true);
          },
          error: (error) => {
            this._messagesService.error('Login', error);
          },
        }
        )
      );
  }

  public logout(): any {
    return this._http.get<any>(this.backend_api + this.auth_api + 'logout')
      .pipe(
        tap(
        {
          next: (data: any)  => {
            this._messagesService.success('Logout', data.message);

            localStorage.removeItem('access_token');
            this.redirect();
          },
          error: (error) => {
            this._messagesService.error('Logout', error);

            localStorage.removeItem('access_token');
            this.redirect();
          },
        }
        )
      );
  }

  public checkToken(): any {
    return this._http.get<any>(this.backend_api + this.auth_api + 'checkToken')
      .pipe(
        tap(
        {
          next: (data: any)  => {
            this._messagesService.success('checkToken', data.message);

            if(data.access_token) this.setToken('access_token', data.access_token);
          },
          error: (error) => {
            this._messagesService.error('checkToken', error);

            localStorage.removeItem('access_token');
            this.redirect();
          },
        }
        )
      );
  }

  protected setToken(type: string, token: string): void {
    localStorage.setItem(type, token)
  }

  protected redirect(force = false){
    if(!this.isAuthenticated()) this._router.navigate(['/']);
    if(force) this._router.navigate([this.redirectUrl]);
  }
}
