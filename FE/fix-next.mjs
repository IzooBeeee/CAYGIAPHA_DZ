import fs from 'fs';
import path from 'path';

function processDirectory(dir) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    const fullPath = path.join(dir, file);
    if (fs.statSync(fullPath).isDirectory()) {
      processDirectory(fullPath);
    } else if (fullPath.endsWith('.tsx') || fullPath.endsWith('.ts') || fullPath.endsWith('.jsx')) {
      let content = fs.readFileSync(fullPath, 'utf-8');
      let modified = false;

      // Replace next/link
      if (content.includes('next/link')) {
        content = content.replace(/import Link from ["']next\/link["']/g, 'import { Link } from "react-router-dom"');
        // Replace href= with to= in <Link> tags
        content = content.replace(/<Link([^>]*)href=/g, '<Link$1to=');
        modified = true;
      }

      // Replace next/image
      if (content.includes('next/image')) {
        content = content.replace(/import Image from ["']next\/image["']/g, '');
        // Replace <Image /> with <img />
        content = content.replace(/<Image([^>]*)\/?>/g, (match, p1) => {
           // We might need to handle self closing or not. React requires self closing for img
           let attrs = p1;
           // If there is `fill` attribute, we might want to replace with class="w-full h-full object-cover"
           attrs = attrs.replace(/\bfill\b/g, 'className="w-full h-full object-cover"');
           return `<img${attrs}/>`;
        });
        modified = true;
      }

      // Replace next/navigation
      if (content.includes('next/navigation')) {
        content = content.replace(/import {([^}]+)} from ["']next\/navigation["']/g, (match, p1) => {
          const imports = p1.split(',').map(s => s.trim());
          const newImports = [];
          if (imports.includes('useRouter')) newImports.push('useNavigate');
          if (imports.includes('usePathname')) newImports.push('useLocation');
          if (imports.includes('useSearchParams')) newImports.push('useSearchParams');
          return `import { ${newImports.join(', ')} } from "react-router-dom"`;
        });

        // Replace useRouter() with useNavigate()
        content = content.replace(/useRouter\(\)/g, 'useNavigate()');
        // Replace router.push with navigate
        content = content.replace(/router\.push/g, 'navigate');
        content = content.replace(/router\.replace/g, 'navigate'); // Note: router.replace(path) -> navigate(path, {replace:true}) might need manual fix, but this is a good start.
        content = content.replace(/router\.refresh\(\)/g, 'window.location.reload()');
        content = content.replace(/const router =/g, 'const navigate =');

        // Replace usePathname() with useLocation().pathname
        content = content.replace(/usePathname\(\)/g, 'useLocation().pathname');

        modified = true;
      }

      // Replace Supabase imports
      if (content.includes('@/utils/supabase')) {
        content = content.replace(/import .* from ["']@\/utils\/supabase.*?["']/g, '');
        content = content.replace(/const supabase = await getSupabase\(\);?/g, '');
        modified = true;
      }

      if (modified) {
        fs.writeFileSync(fullPath, content, 'utf-8');
        console.log('Modified:', fullPath);
      }
    }
  }
}

processDirectory('/Users/vannhan/CAYGIAPHA/FE/src');
console.log('Done replacing nextjs imports');
