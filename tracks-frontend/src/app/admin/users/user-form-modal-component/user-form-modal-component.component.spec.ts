import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserFormModalComponentComponent } from './user-form-modal-component.component';

describe('UserFormModalComponentComponent', () => {
  let component: UserFormModalComponentComponent;
  let fixture: ComponentFixture<UserFormModalComponentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserFormModalComponentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserFormModalComponentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
