import { Component } from '@angular/core';
import { MenuItem } from 'primeng/api';

@Component({
  selector: 'app-breadcrumb',
  templateUrl: './breadcrumb.component.html',
  styleUrls: ['./breadcrumb.component.scss']
})
export class BreadcrumbComponent {
    items: MenuItem[];

    home: MenuItem;

    constructor() {
        this.items = [{ label: 'Uni Bologna' }, { label: 'Medicina Generale' }, { label: '2023' }, { label: 'Mario' }];

        this.home = { icon: 'pi pi-home', routerLink: '/home' };
    }
}
