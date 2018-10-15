package java_11;
// Name: JAHJA Darwin
// StudentID: 16094501d

public class LCS16094501D {

    private static final byte ULI = 0;
    private static final byte UP = 1;
    private static final byte LEFT = 2;
    private static final byte IN = 3;

    public static String FindLCS(String X, String Y, String Z) {
        final int lx = X.length();
        final int ly = Y.length();
        final int lz = Z.length();

        // Setup count table and trace-back table
        int count[][][] = new int[lx+1][ly+1][lz+1];
        byte trace[][][] = new byte[lx+1][ly+1][lz+1];
        int a, b, c;

        // Initialize the base cases
        for(a = 1; a < lx+1; a++) {
            for(b = 0; b < ly+1; b++) {
                count[a][b][0] = 0;
                trace[a][b][0] = UP;
            }
        }
        for(b = 1; b < ly+1; b++) {
            for(c = 0; c < lz+1; c++) {
                count[0][b][c] = 0;
                trace[0][b][c] = LEFT;
            }
        }
        for(c = 1; c < lz+1; c++) {
            for(a = 0; a < lx+1; a++) {
                count[a][0][c] = 0;
                trace[a][0][c] = IN;
            }
        }

        // Fill in the count and trace-back table based on the recurrence for an LCS
        for(a = 1; a < lx+1; a++)
        for(b = 1; b < ly+1; b++)
        for(c = 1; c < lz+1; c++) {
            if(X.charAt(a-1) == Y.charAt(b-1) && X.charAt(a-1) == Z.charAt(c-1)) {
                count[a][b][c] = count[a-1][b-1][c-1] + 1;
                trace[a][b][c] = ULI;
            } else { // Find max
                count[a][b][c] = count[a-1][b][c];
                trace[a][b][c] = UP;

                if(count[a][b-1][c] >= count[a][b][c]) {
                    count[a][b][c] = count[a][b-1][c];
                    trace[a][b][c] = LEFT;
                }
                if (count[a][b][c-1] >= count[a][b][c]) {
                    count[a][b][c] = count[a][b][c-1];
                    trace[a][b][c] = IN;
                }
            }
        }

        // Construct the LCS using the trace-back table
        StringBuilder lcs = new StringBuilder();
        for(a=lx, b=ly, c=lz; a>0 || b>0 || c>0; ) {
            switch(trace[a][b][c]) {
                case ULI:
                    a--; b--; c--;
                    lcs.insert(0, X.charAt(a));
                    break;
                case UP:
                    a--; break;
                case LEFT:
                    b--; break;
                case IN:
                    c--; break;
            }
        }
        return lcs.toString();
    }

    public static void main(String[] args) {
        try {
            String res = FindLCS(args[0], args[1],args[2]);
            System.out.println(res);
        } catch (Exception e) {
            System.out.println("Error: missing cmd arguments, require 3 strings");
        }
    }
}
