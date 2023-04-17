import { Component, OnInit } from '@angular/core';
import { ChildrenOutletContexts, Router } from '@angular/router';
import { AuthService } from '../auth/services/auth.service';
import { slideInAnimation } from '../shared/utils/animations';
import { Subscription, map, timer } from 'rxjs';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
  animations: [ slideInAnimation ]
})
export class AppComponent implements OnInit {

  public title = 'Area Riservata';
  private timerSubscription: Subscription | undefined;

  constructor(
    private _contexts: ChildrenOutletContexts,
    public authService: AuthService,
    public router: Router,
  ) {}

  ngOnInit(): void {
     this.timerSubscription = timer(0, 30000).pipe(
      map(() => {
        if(this.router.url == '/login') return;
        this.authService.checkToken().subscribe(
          (res: any) => {},
          (err: any) => {}
        );
      })
    ).subscribe();
  }

  getRouteAnimationData() {
    return this._contexts.getContext('primary')?.route?.snapshot?.data?.['animation'];
  }

  logout() {
    this.authService.logout().subscribe(
      (res: any) => {},
      (err: any) => {}
    );
  }

  ngOnDestroy(): void {
    this.timerSubscription?.unsubscribe();
  }

}
