import {Injectable, InjectionToken} from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class StorageManager implements StorageManagerInterface {
  clear(): void {
    localStorage.clear();
  }

  set(name: string, value: string): void {
    localStorage.setItem(name, value);
  }

  get(name: string): string | null {
    return localStorage.getItem(name);
  }

  remove(name: string): void {
    localStorage.removeItem(name);
  }
}

export interface StorageManagerInterface {
  clear(): void;

  set(name: string, value: string): void;

  get(name: string): string | null;

  remove(name: string): void;
}

export const STORAGE_MANAGER = new InjectionToken<StorageManagerInterface>('StorageManagerInterface');

export const STORAGE_MANAGER_PROVIDER = {
  provide: STORAGE_MANAGER,
  useClass: StorageManager
};
