package java_11;
// Name: JAHJA Darwin
// StudentID: 16094501d
// Sequence test

import java.util.Random;

public class LCS_Q4 {

    private static String getRandomBinaryString(int num) {
        StringBuilder bin = new StringBuilder();
        Random rand = new Random();
        for(int i = 0; i < num; i++) {
            bin.append((rand.nextInt() & Integer.MAX_VALUE) % 2);
        }
        return bin.toString();
    }

    public static void main(String argv[]) {
        final int[] nlist = {20, 50, 100, 150, 200, 400, 600, 700, 800, 900};
        for(int n : nlist) {
            double sum = 0;
            for(int i = 0; i < 10; i++) {
                int len = LCS16094501D.FindLCS(
                        getRandomBinaryString(n),
                        getRandomBinaryString(n),
                        getRandomBinaryString(n)
                ).length();
                sum += len;
                System.out.print(len + ",");
            }
            double average = sum / 10;
            double ratio = average / n;
            System.out.println("// n = "+ n +", A(n) = "+ average +", A(n)/n = "+ ratio);
        }
    }
}
