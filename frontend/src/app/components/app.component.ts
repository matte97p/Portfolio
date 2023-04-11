import { Component } from '@angular/core';
import { ChildrenOutletContexts, Router } from '@angular/router';
import { AuthService } from '../auth/services/auth.service';
import { slideInAnimation } from '../shared/utils/animations';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
  animations: [ slideInAnimation ]
})
export class AppComponent {

  public title = 'Area Riservata'

  constructor(
    private _contexts: ChildrenOutletContexts,
    public authService: AuthService,
    public router: Router,
  ) {}

  getRouteAnimationData() {
    return this._contexts.getContext('primary')?.route?.snapshot?.data?.['animation'];
  }

  logout() {
    this.authService.logout().subscribe(
      ( res: any) => {
        if (!this.authService.isAuthenticated()) {
          this.router.navigate(['/']);
        }
      },
      ( err: any) => {
        //
      }
    );
  }

  checkToken(){
    this.authService.checkToken().subscribe(
      ( res: any) => {
        if (!this.authService.isAuthenticated()) {
          this.router.navigate(['/']);
        }
      },
      ( err: any) => {
      }
    );
  }

}
