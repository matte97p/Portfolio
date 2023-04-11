import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { tap } from 'rxjs/operators';
import { MessagesService } from 'src/app/shared/services/messages.service';

@Injectable({
  providedIn: 'root',
})
export class AuthService {

  private backend_api = 'https://backend-portfolio.test/';
  private auth_api = 'auth/';
  private redirectUrl: string | null = null;

  constructor(
    private _http: HttpClient,
    private _messagesService: MessagesService
  ) {
    this.redirectUrl = '/home'; // @todo get from role
  }

  static getToken() {
    return localStorage.getItem('access_token')
  }

  get getRedirectUrl() {
    return this.redirectUrl
  }

  public isAuthenticated(): boolean {
    return !!localStorage.getItem('access_token')
  }

  public login(formData: any): any {
    return this._http.post<any>(this.backend_api + this.auth_api + 'oauth2', formData)
      .pipe(
        tap(
        {
          next: (data: any) => {
            this.setToken('access_token', data.access_token);
            // this.setToken('refresh_token', data.refresh_token);

            this._messagesService.success('Login', 'Login effettuato con successo!');
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
            localStorage.removeItem('access_token');
            // localStorage.removeItem('refresh_token');

            this._messagesService.success('Logout', data.message);
          },
          error: (error) => {
            this._messagesService.error('Logout', error);
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
          },
          error: (error) => {
            this._messagesService.error('checkToken', error);
          },
        }
        )
      );
  }

  protected setToken (type: string, token: string): void {
    localStorage.setItem(type, token)
  }
}
